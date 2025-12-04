<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Student\StudentController; // تم إضافة هذا السطر
use App\Http\Controllers\Teacher\TeacherController;
use App\Http\Controllers\Teacher\AttendanceSessionController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------|
| الصفحة الرئيسية (Welcome)                                               |
|--------------------------------------------------------------------------|
*/
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
});

/*
|--------------------------------------------------------------------------|
| مجموعة الإدارة (Admin)                                                  |
|--------------------------------------------------------------------------|
*/
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // لوحة التحكم
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // إدارة المستخدمين
        Route::get('/users',               [AdminController::class, 'usersIndex'])->name('users.index');
        Route::get('/users/create',        [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users',              [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit',   [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}',        [AdminController::class, 'updateUser'])->name('users.update');

        // إدارة الطلاب
        Route::get('/students',                 [AdminController::class, 'studentsIndex'])->name('students.index');
        Route::get('/students/create',          [AdminController::class, 'createStudent'])->name('students.create');
        Route::post('/students',                [AdminController::class, 'storeStudent'])->name('students.store');
        Route::get('/students/{student}/edit',  [AdminController::class, 'editStudent'])->name('students.edit');
        Route::put('/students/{student}',       [AdminController::class, 'updateStudent'])->name('students.update');
        Route::delete('/students/{student}',    [AdminController::class, 'destroyStudent'])->name('students.destroy');

        // ترميز وجوه الطلاب
        Route::post('/students/encode-faces', [AdminController::class, 'encodeFaces'])->name('students.encode');

        // الدورات (Courses)
        Route::get('/courses',                [AdminController::class, 'coursesIndex'])->name('courses.index');
        Route::get('/courses/create',         [AdminController::class, 'coursesCreate'])->name('courses.create');
        Route::post('/courses',               [AdminController::class, 'coursesStore'])->name('courses.store');
        Route::get('/courses/{course}/edit',  [AdminController::class, 'editCourse'])->name('courses.edit');
        Route::put('/courses/{course}',       [AdminController::class, 'updateCourse'])->name('courses.update');
        Route::delete('/courses/{course}',    [AdminController::class, 'destroyCourse'])->name('courses.destroy');

        // الحضور
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');

        // الجداول (Schedules)
        Route::get('/schedules/export',     [ScheduleController::class, 'export'])->name('schedules.export');       // CSV
        Route::get('/schedules/export-pdf', [ScheduleController::class, 'exportPdf'])->name('schedules.exportPdf'); // PDF

        // CRUD كامل للجداول
        Route::resource('schedules', ScheduleController::class);
    });

/*
|--------------------------------------------------------------------------|
| مجموعة المعلّم (Teacher)                                                 |
|--------------------------------------------------------------------------|
*/
Route::middleware(['auth', 'verified', 'role:teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
        Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');

        // صفحة المادة: الطلاب + تسجيل طالب + زر بدء الجلسة
        Route::get('/courses/{course}', [TeacherController::class, 'showCourse'])
            ->name('courses.show');

        // مسار زر START في الداشبورد (نفس صفحة ShowCourse)
        Route::get('/courses/{course}/students', [TeacherController::class, 'showCourse'])
            ->name('courses.add_student');

        // إدارة الطلاب من جهة المعلم
        Route::get('/students/create',  [TeacherController::class, 'createStudent'])->name('students.create');
        Route::post('/students',        [TeacherController::class, 'storeStudent'])->name('students.store');

        // إنشاء مواد من جهة المعلم
        Route::get('/courses/create',           [TeacherController::class, 'coursesCreate'])->name('courses.create');
        Route::post('/courses',                 [TeacherController::class, 'coursesStore'])->name('courses.store');
        Route::post('/courses/{course}/enroll', [TeacherController::class, 'enrollStudent'])->name('courses.enroll');

        // الجداول الخاصة بالمعلم
        Route::post('/courses/{course}/schedules', [TeacherController::class, 'storeSchedule'])->name('schedules.store');

        // جلسة الحضور على مستوى الـ Course
        Route::get('/courses/{course}/attendance',       [TeacherController::class, 'startAttendanceSession'])->name('courses.attendance');
        Route::get('/courses/{course}/attendance/start', [TeacherController::class, 'startAttendanceSession'])->name('attendance.start');

        // جلسة الحضور على مستوى الـ Schedule — للزر START في الداشبورد (لو استعملته مستقبلاً)
        Route::get('/attendance/session/{schedule}', [AttendanceSessionController::class, 'create'])
            ->name('attendance.session.create');
        Route::post('/attendance/session', [AttendanceSessionController::class, 'store'])
            ->name('attendance.session.store');

        // التعرّف + التسجيل
        Route::post('/attendance/recognize', [TeacherController::class, 'recognize'])->name('attendance.recognize');
        Route::post('/attendance/mark',      [TeacherController::class, 'markAttendance'])->name('attendance.mark');

        // إنهاء الجلسة
        Route::post('/courses/{course}/attendance/end', [TeacherController::class, 'endAttendanceSession'])->name('attendance.end');

        // إحصائيات اليوم
        Route::get('/stats/today', [TeacherController::class, 'todayStats'])->name('stats.today');

        // مسار سجل الحضور للمادة
        Route::get('/courses/{course}/attendance-records', [TeacherController::class, 'attendanceRecords'])->name('courses.attendanceRecords');
    });

/*
|--------------------------------------------------------------------------|
| مجموعة الطالب (Student)                                                 |
|--------------------------------------------------------------------------|
*/
Route::middleware(['auth', 'verified', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard',        [StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('/courses/{course}', [StudentController::class, 'showCourse'])->name('courses.show');

        // صفحات الطالب
        Route::get('/schedule',           [StudentController::class, 'schedule'])->name('schedule');
        Route::get('/notifications',      [StudentController::class, 'notifications'])->name('notifications');
        Route::get('/attendance-records', [StudentController::class, 'attendanceRecords'])->name('attendance.records');
    });

/*
|--------------------------------------------------------------------------|
| التوجيه حسب الدور بعد تسجيل الدخول                                   |
|--------------------------------------------------------------------------|
*/
Route::get('/dashboard', function () {
    $user = Auth::user();

    $role = $user->role instanceof \BackedEnum ? $user->role->value : (string) $user->role;
    $role = strtolower(trim($role));

    if ($role === 'admin')   return redirect()->route('admin.dashboard');
    if ($role === 'teacher') return redirect()->route('teacher.dashboard');
    if ($role === 'student') return redirect()->route('student.dashboard');

    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------|
| الملف الشخصي + المصادقات                                               |
|--------------------------------------------------------------------------|
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',[ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
