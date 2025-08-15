<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Course; // Import the Course model
use App\Models\User; // Import the User model
use App\Enums\UserRole; // Import the UserRole enum
use Illuminate\Validation\Rule; // Import Rule for validation
use Illuminate\Support\Facades\Hash;
// Import the UserRole enum for validation
use Illuminate\Validation\Rules; // Import Rules for password validation
use App\Models\Attendance; // Import the Attendance model
use Carbon\Carbon; // Import Carbon for date handling
use Illuminate\Support\Facades\Http; // Import Http facade for making HTTP requests
use App\Models\Schedule; // Import the Schedule model


class TeacherController extends Controller
{
    public function dashboard()
    {
        // Get the currently authenticated user (the teacher)
        $teacher = Auth::user();

        // Load the courses they are teaching using the relationship we defined
        $courses = $teacher->teachingCourses()->get();

        return Inertia::render('Teacher/Dashboard', [
            'courses' => $courses,
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
            'course' => $course,
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
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        $course->schedules()->create($request->all());

        return redirect()->route('teacher.courses.show', $course->id)->with('success', 'Schedule added successfully.');
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
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:50|unique:courses',
        'description' => 'nullable|string',
    ]);

    // Create the course and automatically assign the logged-in teacher's ID
    Auth::user()->teachingCourses()->create($request->all());

    return redirect()->route('teacher.dashboard')->with('success', 'Course created successfully.');
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

    return redirect()->route('teacher.courses.show', $course->id)->with('success', 'Student enrolled successfully.');
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
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'photos' => 'required|array|min:1', // Ensure photos array is present and has at least 1 image
        'photos.*' => 'image|mimes:jpeg,png,jpg|max:2048', // Validate each photo
    ]);

    // Create the student first
    $student = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => UserRole::STUDENT,
    ]);

    // Handle photo uploads
    if ($request->hasFile('photos')) {
        foreach ($request->file('photos') as $photo) {
            // Store the file and get its path
            // The path will be something like 'student_photos/filename.jpg'
            $path = $photo->store('student_photos', 'public');

            // Create a record in the student_photos table
            $student->photos()->create(['photo_path' => $path]);
        }
    }

    return redirect()->route('teacher.dashboard')->with('success', 'Student created successfully with photos.');
}

public function startAttendanceSession(Course $course)
{
    // Ensure the teacher owns the course
    if ($course->teacher_id !== Auth::id()) {
        abort(403);
    }

    // Get the current day and find the corresponding schedule
    $todayName = Carbon::now()->format('l'); // e.g., "Sunday"
    $schedule = $course->schedules()->where('day_of_week', $todayName)->first();

    if (!$schedule) {
        return back()->with('error', "There is no scheduled lecture for this course today ({$todayName}).");
    }

    // Get all students enrolled in the course
    $students = $course->students()->get();

    // Create or find attendance records for today for all students
    foreach ($students as $student) {
        Attendance::firstOrCreate(
            [
                'student_id' => $student->id,
                'schedule_id' => $schedule->id,
                'attendance_date' => Carbon::today(),
            ],
            ['is_present' => false] // Default to absent
        );
    }

    // Fetch the fresh attendance records for today
    $todaysAttendance = Attendance::where('schedule_id', $schedule->id)
                                  ->whereDate('attendance_date', Carbon::today())
                                  ->with('student')
                                  ->get();

    return Inertia::render('Teacher/Attendance/Session', [
        'course' => $course,
        'schedule' => $schedule,
        'todaysAttendance' => $todaysAttendance,
    ]);
}

public function markAttendance(Request $request)
{
    $request->validate([
        'image' => 'required|image',
        'schedule_id' => 'required|exists:schedules,id',
    ]);

    try {
        $response = Http::attach(
            'image', file_get_contents($request->file('image')), 'photo.jpg'
        )->post('http://127.0.0.1:5000/recognize-face');

        if ($response->successful() && $response->json('student_id')) {
            $studentId = $response->json('student_id');

            $attendance = Attendance::where('student_id', $studentId)
                                    ->where('schedule_id', $request->schedule_id)
                                    ->whereDate('attendance_date', Carbon::today())
                                    ->first();

            if ($attendance) {
                // If student is not yet marked present, record their arrival
                if (!$attendance->is_present) {
                    $attendance->update(['is_present' => true, 'attended_at' => now()]);
                    return response()->json(['status' => 'arrived', 'student_id' => $studentId]);
                } 
                // If student is already present and not yet departed, record their departure
                elseif ($attendance->is_present && is_null($attendance->departed_at)) {
                    $attendance->update(['departed_at' => now()]);
                    return response()->json(['status' => 'departed', 'student_id' => $studentId]);
                }
            }
        }

        return response()->json(['status' => 'not_recognized'], 404);

    } catch (\Exception $e) {
        return response()->json(['status' => 'service_unavailable'], 503);
    }
}
}