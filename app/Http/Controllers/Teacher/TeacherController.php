<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Models\Schedule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\OtpService;
use App\Mail\AttendanceOtpMail;
use Barryvdh\DomPDF\Facade\Pdf;

class TeacherController extends Controller
{
    public function dashboard()
    {
        // Get the currently authenticated user (the teacher)
        $teacher = Auth::user();

        // Load the courses they are teaching using the relationship we defined
        $courses = $teacher->teachingCourses()
            ->with('schedules') // مهم جداً
            ->get();

        return Inertia::render('Teacher/Dashboard', [
            'teacherName' => $teacher->name,
            'courses'     => $courses,
            'today'       => Carbon::now()->format('l'), // Sunday, Monday, ...
            // لو عندك stats و summaries ضيفهم هنا كالعادة
        ]);
    }

    public function showCourse(Course $course)
    {
        if ($course->teacher_id !== Auth::id()) {
            abort(403);
        }

        $course->load('students', 'schedules');

        // Get IDs of students already enrolled
        $enrolledStudentIds = $course->students->pluck('id');

        // Get all students who are not enrolled in this course
        $unenrolledStudents = User::where('role', UserRole::STUDENT)
            ->whereNotIn('id', $enrolledStudentIds)
            ->orderBy('name')
            ->get();

        return Inertia::render('Teacher/ShowCourse', [
            'course'             => $course,
            'unenrolledStudents' => $unenrolledStudents,
        ]);
    }

    public function storeSchedule(Request $request, Course $course)
    {
        // Ensure the authenticated teacher owns this course
        if ($course->teacher_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'day_of_week' => ['required', 'in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday'],
            'start_time'  => ['required', 'date_format:H:i'],
            'end_time'    => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        $course->schedules()->create($request->all());

        return redirect()
            ->route('teacher.courses.show', $course->id)
            ->with('success', 'Schedule added successfully.');
    }

    // In TeacherController.php

    public function coursesCreate()
    {
        // Simply render the creation form view
        return Inertia::render('Teacher/CreateCourse');
    }

    public function coursesStore(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:courses',
            'description' => 'nullable|string',
        ]);

        // Create the course and automatically assign the logged-in teacher's ID
        Auth::user()->teachingCourses()->create($request->all());

        return redirect()
            ->route('teacher.dashboard')
            ->with('success', 'Course created successfully.');
    }

    public function enrollStudent(Request $request, Course $course)
    {
        if ($course->teacher_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'student_id' => ['required', Rule::exists('users', 'id')->where('role', 'student')],
        ]);

        // Attach the student to the course
        $course->students()->attach($request->student_id);

        return redirect()
            ->route('teacher.courses.show', $course->id)
            ->with('success', 'Student enrolled successfully.');
    }

    public function createStudent()
    {
        return Inertia::render('Teacher/Students/Create');
    }

    /**
     * Store a newly created student in storage.
     */
    public function storeStudent(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'photos'   => 'required|array|min:1',
            'photos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Create the student first
        $student = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => UserRole::STUDENT,
        ]);

        // Handle photo uploads
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('student_photos', 'public');
                $student->photos()->create(['photo_path' => $path]);
            }
        }

        return redirect()
            ->route('teacher.dashboard')
            ->with('success', 'Student created successfully with photos.');
    }

   public function startAttendanceSession(Course $course)
{
    // التحقق من ملكية المادة
    if ($course->teacher_id !== Auth::id()) {
        abort(403);
    }

    // تحديد اليوم الحالي
    $todayName = Carbon::now()->format('l');

    // جلب جدول اليوم
    $schedule = $course->schedules()
        ->where('day_of_week', $todayName)
        ->first();

    if (!$schedule) {
        return back()->with(
            'error',
            "There is no scheduled lecture for this course today ({$todayName})."
        );
    }

    // طلاب المادة
    $students = $course->students()->get();

    // إنشاء سجلات الحضور
    foreach ($students as $student) {
        Attendance::firstOrCreate(
            [
                'student_id'      => $student->id,
                'schedule_id'     => $schedule->id,
                'course_id'       => $course->id,
                'attendance_date' => Carbon::today(),
            ],
            [
                'is_present' => false,
            ]
        );
    }

    // جلب سجلات اليوم
    $todaysAttendance = Attendance::where('schedule_id', $schedule->id)
        ->whereDate('attendance_date', Carbon::today())
        ->with('student')
        ->get();

    return Inertia::render('Teacher/Attendance/Session', [
        'course'           => $course,
        'schedule'         => $schedule,
        'todaysAttendance' => $todaysAttendance,
    ]);
}


  public function markAttendance(Request $request)
{
    $request->validate([
        'image'       => 'required|image',
        'schedule_id' => 'required|exists:schedules,id',
    ]);

    try {
        $response = Http::attach(
            'image',
            file_get_contents($request->file('image')),
            'frame.jpg'
        )->post('http://127.0.0.1:5000/recognize-face');

        if ($response->successful()) {
            $studentId = $response->json('student_id');

            $attendance = Attendance::where('schedule_id', $request->schedule_id)
                ->where('student_id', $studentId)
                ->whereDate('attendance_date', today())
                ->first();

            if ($attendance && !$attendance->is_present) {
                $attendance->update([
                    'is_present'  => true,
                    'attended_at' => now(),
                ]);
            }

            return response()->json([
                'status'     => 'success',
                'student_id' => $studentId,
            ]);
        }
    } catch (\Exception $e) {
        // ⬅️ لا ترجع 500
        Log::warning('Flask down, switching to OTP mode');
    }

    // 🔐 مسار OTP (Fallback)
    $attendance = Attendance::where('schedule_id', $request->schedule_id)
        ->whereDate('attendance_date', today())
        ->where('is_present', false)
        ->first();

    if ($attendance) {
        $otpCode = app(\App\Services\OtpService::class)
            ->generate($attendance->student, $attendance);

        \Mail::to($attendance->student->email)
            ->send(new \App\Mail\AttendanceOtpMail($otpCode));

        return response()->json([
            'status'        => 'otp_required',
            'attendance_id'=> $attendance->id,
        ], 200);
    }

    return response()->json(['status' => 'not_recognized'], 404);
}

    // عرض سجل الحضور للمادة (واجهة Inertia)
    public function attendanceRecords($courseId)
    {
        // استرجاع المادة وبداخلها الجداول
        $course = Course::with('schedules')->findOrFail($courseId);

        // تأكد أن المعلّم الحالي صاحب المادة
        if ($course->teacher_id !== Auth::id()) {
            abort(403);
        }

        // IDs للجداول (schedules) التابعة للمادة
        $scheduleIds = $course->schedules->pluck('id');

        // استرجاع سجلات الحضور المرتبطة بهذه الجداول
        $attendanceRecords = Attendance::with(['student', 'schedule'])
            ->whereIn('schedule_id', $scheduleIds)
            ->orderByDesc('attendance_date')
            ->get();

        // تمرير البيانات إلى واجهة المعلم باستخدام Inertia
        return Inertia::render('Teacher/AttendanceRecords', [
            'course'            => $course,
            'attendanceRecords' => $attendanceRecords,
        ]);
    }

    // ✅ إنشاء ملف PDF لسجل الحضور
    public function attendanceRecordsPdf(Course $course)
    {
        // تأكد أن المعلّم الحالي صاحب المادة
        if ($course->teacher_id !== Auth::id()) {
            abort(403);
        }

        // تحميل الجداول التابعة للمادة
        $course->load('schedules');

        // IDs للجداول
        $scheduleIds = $course->schedules->pluck('id');

        // سجلات الحضور
        $attendanceRecords = Attendance::with(['student', 'schedule'])
            ->whereIn('schedule_id', $scheduleIds)
            ->orderByDesc('attendance_date')
            ->get();


        $pdf = Pdf::loadView('teacher.attendance.records_pdf', [
            'course'            => $course,
            'attendanceRecords' => $attendanceRecords,
        ]);

        $fileName = 'attendance_' . ($course->code ?? $course->id) . '.pdf';

        return $pdf->download($fileName);
    }

    public function endAttendanceSession(Course $course, Request $request)
{
    // التحقق من ملكية المادة
    if ($course->teacher_id !== Auth::id()) {
        abort(403);
    }

    // التحقق من البيانات القادمة
    $request->validate([
        'schedule_id' => 'required|exists:schedules,id',
    ]);

    $scheduleId = $request->schedule_id;

    // جلب الطلاب الغائبين
    $absentStudents = Attendance::where('schedule_id', $scheduleId)
        ->whereDate('attendance_date', today())
        ->where('is_present', false)
        ->with('student')
        ->get();

    // خدمة OTP
    $otpService = app(OtpService::class);

    foreach ($absentStudents as $attendance) {

        $student = $attendance->student;

        if (!$student || !$student->email) {
            continue;
        }

        // إنشاء OTP مرتبط بسجل الحضور
        $otpCode = $otpService->generate($student, $attendance);

        // إرسال OTP عبر Mailtrap
        Mail::to($student->email)
            ->send(new AttendanceOtpMail($otpCode));
    }

    return redirect()
        ->route('teacher.courses.show', $course)
        ->with('success', 'Session ended. OTP codes sent to absent students.');
}
}
