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
            'day_of_week' => ['required', 'in:Sunday,Monday,Tuesday,Wednesday,Thursday'],
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
    ]);

    $student = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => UserRole::STUDENT, // Set the role to student automatically
    ]);

    // You can decide where to redirect the teacher after creation.
    // For now, we'll redirect to the dashboard.
    return redirect()->route('teacher.dashboard')->with('success', 'Student created successfully.');
}
}