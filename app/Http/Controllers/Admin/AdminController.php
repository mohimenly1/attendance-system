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
use Illuminate\Support\Facades\Http; // Import Http facade for making HTTP requests

class AdminController extends Controller
{
    public function encodeFaces()
    {
        // 1. Fetch all students with their photos
        $students = User::where('role', UserRole::STUDENT)
                        ->with('photos')
                        ->get();

        if ($students->isEmpty()) {
            return back()->with('error', 'No students found to encode.');
        }

        // 2. Format data for the Python service
        $studentsData = [];
        foreach ($students as $student) {
            if ($student->photos->isNotEmpty()) {
                // Collect all photo paths for the student
                $studentsData[$student->id] = $student->photos->pluck('photo_path')->all();
            }
        }

        if (empty($studentsData)) {
            return back()->with('error', 'No students with photos found.');
        }

        // 3. Send data to the Python API
        try {
            $response = Http::post('http://127.0.0.1:5000/encode-faces', [
                'students' => $studentsData
            ]);

            if ($response->successful()) {
                return back()->with('success', 'Face encoding process started successfully.');
            } else {
                // Provide a more detailed error from the Python service if available
                $errorMessage = $response->json('error', 'An unknown error occurred with the face recognition service.');
                return back()->with('error', "Error: " . $errorMessage);
            }

        } catch (\Exception $e) {
            // Handle cases where the Python service is not running
            return back()->with('error', 'Could not connect to the face recognition service. Please ensure it is running.');
        }
    }
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