<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User; // Import the User model
use App\Models\Course; // Import the Course model
use App\Enums\UserRole; // Import the UserRole enum
use Illuminate\Validation\Rule; // Import Rule for validation
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules; // Import Rules for password validation

class AdminController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('admin/Dashboard');
    }
        // --- Add this new method ---
        public function usersIndex()
        {
            // Fetch all users except the admin (user with ID 1, for example)
            $users = User::where('id', '!=', 1)
                         ->orderBy('role')
                         ->orderBy('name')
                         ->get();
    
            return Inertia::render('admin/users/Index', [
                'users' => $users,
            ]);
        }

        public function coursesIndex()
        {
            $courses = Course::with('teacher')->latest()->get();
            return Inertia::render('admin/courses/Index', [
                'courses' => $courses,
            ]);
        }
    
        public function coursesCreate()
        {
            // Fetch only users who are teachers
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

        
        public function studentsIndex()
        {
            // Fetch only users with the 'student' role
            // Eager load their courses and photos relationships
            $students = User::where('role', UserRole::STUDENT)
                             ->with('courses', 'photos') // Load courses and photos
                             ->orderBy('name')
                             ->get();
        
            return Inertia::render('admin/students/Index', [
                'students' => $students,
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

    return redirect()->route('admin.dashboard')->with('success', 'Student created successfully with photos.');
}
        
}