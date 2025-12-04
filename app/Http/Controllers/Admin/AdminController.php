<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Course;
use App\Models\Attendance;
use App\Enums\UserRole;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    // -------------------- DASHBOARD --------------------
    public function dashboard()
    {
        $totalStudents = User::where('role', UserRole::STUDENT)->count();
        $totalTeachers = User::where('role', UserRole::TEACHER)->count();
        $totalCourses = Course::count();

        $recentCheckIns = Attendance::with('student')
            ->where('is_present', true)
            ->orderBy('attended_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($attendance) {
                return [
                    'name' => $attendance->student->name,
                    'time' => $attendance->attended_at ? $attendance->attended_at->format('h:i A') : 'N/A',
                ];
            });

        $studentsExceeded = DB::table('attendances')
            ->select('student_id', DB::raw('COUNT(*) as total_absences'))
            ->where('is_present', false)
            ->groupBy('student_id')
            ->having('total_absences', '>=', 3)
            ->get();

        $notifications = $studentsExceeded->map(function ($record) {
            $student = User::find($record->student_id);
            return [
                'type' => 'alert',
                'message' => $student
                    ? "⚠️ الطالب {$student->name} تجاوز الحد المسموح للغياب ({$record->total_absences} مرات)"
                    : "Student ID {$record->student_id} exceeded absences",
                'icon' => 'fa-exclamation-triangle',
            ];
        });

        $labels = [];
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $labels[] = $day->format('D');
            $total = Attendance::whereDate('attendance_date', $day)->count();
            $present = Attendance::whereDate('attendance_date', $day)
                ->where('is_present', true)
                ->count();
            $data[] = $total > 0 ? round(($present / $total) * 100, 1) : 0;
        }

        $chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Weekly Attendance',
                    'data' => $data,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
        ];

        return Inertia::render('admin/Dashboard', [
            'stats' => [
                'totalStudents' => $totalStudents,
                'totalTeachers' => $totalTeachers,
                'totalCourses' => $totalCourses,
            ],
            'recentCheckIns' => $recentCheckIns,
            'notifications' => $notifications,
            'chartData' => $chartData,
        ]);
    }

    // -------------------- ENCODE STUDENT FACES --------------------
    public function encodeFaces()
    {
        $students = User::where('role', UserRole::STUDENT)
            ->with('photos')
            ->get();

        if ($students->isEmpty()) {
            return back()->with('error', 'No students found to encode.');
        }

        $studentsData = [];
        foreach ($students as $student) {
            if ($student->photos->isNotEmpty()) {
                $studentsData[$student->id] = $student->photos->pluck('photo_path')->all();
            }
        }

        if (empty($studentsData)) {
            return back()->with('error', 'No students with photos found.');
        }

        try {
            $response = Http::post('http://127.0.0.1:5000/encode-faces', [
                'students' => $studentsData,
            ]);

            if ($response->successful()) {
                return back()->with('success', 'Face encoding process started successfully.');
            } else {
                $errorMessage = $response->json('error', 'An unknown error occurred with the face recognition service.');
                return back()->with('error', "Error: " . $errorMessage);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Could not connect to the face recognition service. Please ensure it is running.');
        }
    }

    // -------------------- USERS MANAGEMENT --------------------
    public function usersIndex()
    {
        $users = User::where('id', '!=', 1)
            ->orderBy('role')
            ->orderBy('name')
            ->get();

        return Inertia::render('admin/users/Index', [
            'users' => $users,
        ]);
    }

    public function createUser()
    {
        return Inertia::render('admin/users/Create');
    }

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => ['required', Rule::in([UserRole::ADMIN, UserRole::TEACHER, UserRole::STUDENT])],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function editUser(User $user)
    {
        return Inertia::render('admin/users/Edit', [
            'user' => $user,
        ]);
    }

    public function updateUser(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in([UserRole::ADMIN, UserRole::TEACHER, UserRole::STUDENT])],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $data['role'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    // -------------------- COURSES --------------------
    public function coursesIndex(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $courses = Course::query()
            ->with('teacher:id,name')
            ->when($q !== '', function ($builder) use ($q) {
                $builder->where(function ($b) use ($q) {
                    $b->where('name', 'like', "%{$q}%")
                      ->orWhere('code', 'like', "%{$q}%")
                      ->orWhereHas('teacher', fn($t) => $t->where('name', 'like', "%{$q}%"));
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('admin/courses/Index', [
            'courses' => $courses,
            'filters' => ['q' => $q],
        ]);
    }

    public function coursesCreate()
    {
        $teachers = User::where('role', UserRole::TEACHER)->orderBy('name')->get();

        return Inertia::render('admin/courses/Create', [
            'teachers' => $teachers,
        ]);
    }

    public function coursesStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:courses',
            'description' => 'nullable|string',
            'teacher_id' => ['required', 'integer', Rule::exists('users', 'id')->where('role', 'teacher')],
        ]);

        Course::create($request->all());

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
    }

    public function editCourse(Course $course)
    {
        $teachers = User::where('role', UserRole::TEACHER)->orderBy('name')->get();

        return Inertia::render('admin/courses/Edit', [
            'course' => $course,
            'teachers' => $teachers,
        ]);
    }

    public function updateCourse(Request $request, Course $course)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', Rule::unique('courses')->ignore($course->id)],
            'description' => 'nullable|string',
            'teacher_id' => ['required', 'integer', Rule::exists('users', 'id')->where('role', 'teacher')],
        ]);

        $course->update($request->only(['name', 'code', 'description', 'teacher_id']));

        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroyCourse(Course $course)
    {
        $course->delete();
        return back()->with('success', 'Course deleted successfully.');
    }

    // -------------------- STUDENTS --------------------
    public function studentsIndex(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $students = User::where('role', UserRole::STUDENT)
            ->with('courses', 'photos')
            ->when($q !== '', function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('admin/students/Index', [
            'students' => $students,
            'filters' => ['q' => $q],
        ]);
    }

    public function createStudent()
    {
        return Inertia::render('admin/students/Create');
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'photos' => 'required|array|min:1',
            'photos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $student = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => UserRole::STUDENT,
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('student_photos', 'public');
                $student->photos()->create(['photo_path' => $path]);
            }
        }

        return redirect()->route('admin.students.index')->with('success', 'Student created successfully.');
    }

    public function editStudent(User $student)
    {
        return Inertia::render('admin/students/Edit', [
            'student' => $student->load('photos'),
        ]);
    }

    public function updateStudent(Request $request, User $student)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($student->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $student->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => !empty($data['password'])
                ? Hash::make($data['password'])
                : $student->password,
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('student_photos', 'public');
                $student->photos()->create(['photo_path' => $path]);
            }
        }

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }

    public function destroyStudent(User $student)
    {
        // حذف صور الطالب
        foreach ($student->photos as $photo) {
            \Storage::disk('public')->delete($photo->photo_path);
            $photo->delete();
        }

        $student->delete();
        return back()->with('success', 'Student deleted successfully.');
    }
}
