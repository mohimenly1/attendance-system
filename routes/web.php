<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ScheduleController;     // ✅
use App\Http\Controllers\Admin\AttendanceController;   // ✅
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Teacher\TeacherController;
use Illuminate\Support\Facades\Auth;                   // 🔑

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
});

/*
|--------------------------------------------------------------------------
| مجموعة الإدارة (Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // --- إدارة المستخدمين ---
        Route::get('/users',               [AdminController::class, 'usersIndex'])->name('users.index');
        Route::get('/users/create',        [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users',              [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit',   [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}',        [AdminController::class, 'updateUser'])->name('users.update');

        // --- إدارة الطلاب ---
        Route::get('/students',                    [AdminController::class, 'studentsIndex'])->name('students.index');
        Route::get('/students/create',             [AdminController::class, 'createStudent'])->name('students.create');
        Route::post('/students',                   [AdminController::class, 'storeStudent'])->name('students.store');
        Route::get('/students/{student}/edit',     [AdminController::class, 'editStudent'])->name('students.edit');
        Route::put('/students/{student}',          [AdminController::class, 'updateStudent'])->name('students.update');
        Route::delete('/students/{student}',       [AdminController::class, 'destroyStudent'])->name('students.destroy');

        // --- ترميز وجوه الطلاب ---
        Route::post('/students/encode-faces', [AdminController::class, 'encodeFaces'])->name('students.encode');

        // --- إدارة الدورات (Courses) ---
        Route::get('/courses',                [AdminController::class, 'coursesIndex'])->name('courses.index');
        Route::get('/courses/create',         [AdminController::class, 'coursesCreate'])->name('courses.create');
        Route::post('/courses',               [AdminController::class, 'coursesStore'])->name('courses.store');
        Route::get('/courses/{course}/edit',  [AdminController::class, 'editCourse'])->name('courses.edit');
        Route::put('/courses/{course}',       [AdminController::class, 'updateCourse'])->name('courses.update');
        Route::delete('/courses/{course}',    [AdminController::class, 'destroyCourse'])->name('courses.destroy'); // ✅ مُضاف: للحذف

        // --- الحضور ---
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');

        // --- الجداول (Schedules) ---
        // CSV
        Route::get('/schedules/export',     [ScheduleController::class, 'export'])->name('schedules.export');
        // PDF
        Route::get('/schedules/export-pdf', [ScheduleController::class, 'exportPdf'])->name('schedules.exportPdf');

        Route::resource('schedules', ScheduleController::class);
    });

/*
|--------------------------------------------------------------------------
| مجموعة المعلّم (Teacher)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
        Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');

        Route::get('/courses/{course}', [TeacherController::class, 'showCourse'])->name('courses.show');
        Route::get('/students/create',  [TeacherController::class, 'createStudent'])->name('students.create');
        Route::post('/students',        [TeacherController::class, 'storeStudent'])->name('students.store');

        Route::get('/courses/create',           [TeacherController::class, 'coursesCreate'])->name('courses.create');
        Route::post('/courses',                 [TeacherController::class, 'coursesStore'])->name('courses.store');
        Route::post('/courses/{course}/enroll', [TeacherController::class, 'enrollStudent'])->name('courses.enroll');

        // الجداول الخاصة بالمعلم
        Route::post('/courses/{course}/schedules', [TeacherController::class, 'storeSchedule'])->name('schedules.store');

        // جلسة الحضور
        Route::get('/courses/{course}/attendance',       [TeacherController::class, 'startAttendanceSession'])->name('attendance.start');
        Route::post('/attendance/mark',                  [TeacherController::class, 'markAttendance'])->name('attendance.mark');
        Route::post('/courses/{course}/attendance/end',  [TeacherController::class, 'endAttendanceSession'])->name('attendance.end');
    });

/*
|--------------------------------------------------------------------------
| مجموعة الطالب (Student)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard',        [StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('/courses/{course}', [StudentController::class, 'showCourse'])->name('courses.show');

        // صفحات الطالب
        Route::get('/schedule',            [StudentController::class, 'schedule'])->name('schedule');
        Route::get('/notifications',       [StudentController::class, 'notifications'])->name('notifications');
        Route::get('/attendance-records',  [StudentController::class, 'attendanceRecords'])->name('attendance.records');
    });

/*
|--------------------------------------------------------------------------
| التوجيه بعد تسجيل الدخول حسب الدور
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'admin')   return redirect()->route('admin.dashboard');
    if ($user->role === 'teacher') return redirect()->route('teacher.dashboard');
    if ($user->role === 'student') return redirect()->route('student.dashboard');

    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| الملف الشخصي + مصادقات
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',[ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
