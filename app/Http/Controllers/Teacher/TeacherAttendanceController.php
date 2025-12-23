<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class TeacherAttendanceController extends Controller
{
    /**
     * عرض سجل الحضور لمادة معيّنة للمعلم الحالي.
     */
    public function attendanceRecords(Course $course)
    {
        // تأكد أن هذا الكورس يخص نفس المعلّم
        if ($course->teacher_id !== Auth::id()) {
            abort(403);
        }

        // تحميل السكيجولات التابعة للكورس
        $course->load('schedules');

        // IDs للسكيجولات
        $scheduleIds = $course->schedules->pluck('id');

        // نجيب سجلات الحضور المرتبطة بهذه السكيجولات
        $attendanceRecords = Attendance::with(['student', 'schedule'])
            ->whereIn('schedule_id', $scheduleIds)
            ->orderByDesc('attendance_date')
            ->get();

        // نرسل البيانات لواجهة Inertia
        return Inertia::render('Teacher/AttendanceRecords', [
            'course'            => $course,
            'attendanceRecords' => $attendanceRecords,
        ]);
    }
}
