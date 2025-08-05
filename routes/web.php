<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Teacher\TeacherController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'usersIndex'])->name('users.index');
     // --- Add Course Management Routes ---
     Route::get('/courses', [AdminController::class, 'coursesIndex'])->name('courses.index');
     Route::get('/courses/create', [AdminController::class, 'coursesCreate'])->name('courses.create');
     Route::post('/courses', [AdminController::class, 'coursesStore'])->name('courses.store');
     Route::get('/students', [AdminController::class, 'studentsIndex'])->name('students.index');
     Route::get('/students/create', [AdminController::class, 'createStudent'])->name('students.create');
     Route::post('/students', [AdminController::class, 'storeStudent'])->name('students.store');

});

Route::middleware(['auth', 'verified', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
    Route::get('/courses/{course}', [TeacherController::class, 'showCourse'])->name('courses.show');
    Route::get('/students/create', [TeacherController::class, 'createStudent'])->name('students.create');
Route::post('/students', [TeacherController::class, 'storeStudent'])->name('students.store');
    // In routes/web.php Teacher's Group
    Route::get('teacher/courses/create', [TeacherController::class, 'coursesCreate'])->name('courses.create');
    Route::post('/courses', [TeacherController::class, 'coursesStore'])->name('courses.store');
    Route::post('/courses/{course}/enroll', [TeacherController::class, 'enrollStudent'])->name('courses.enroll');

   // --- Add this new route for storing a schedule ---
   Route::post('/courses/{course}/schedules', [TeacherController::class, 'storeSchedule'])->name('schedules.store');
});

// You can also create a dedicated student group if you plan to add more routes
Route::middleware(['auth', 'verified', 'role:student'])->prefix('student')->name('student.')->group(function () {
    // Example for future routes: Route::get('/my-attendance', ...);
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
});

// Student specific routes
Route::middleware(['auth', 'verified', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/courses/{course}', [StudentController::class, 'showCourse'])->name('courses.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
