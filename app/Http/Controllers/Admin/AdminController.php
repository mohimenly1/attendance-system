<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User; // Import the User model
use App\Models\Course; // Import the Course model
use App\Enums\UserRole; // Import the UserRole enum
use Illuminate\Validation\Rule; // Import Rule for validation

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

        
}