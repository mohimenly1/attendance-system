<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;

class StudentController extends Controller
{
    /**
     * لوحة تحكم الطالب — تعرض المواد فقط
     */
    public function dashboard()
    {
        $student = Auth::user();

        // مواد الطالب + المدرّس فقط (بدون جداول/حضور)
        $courses = $student->courses()->with(['teacher'])->get();

        // محاولة جلب آخر صورة للطالب من علاقة photos() (إن وُجدت)
        $photo = $student->photos()->latest()->first();
        $path = $photo?->image_path ?? $photo?->path ?? $photo?->photo_path ?? null;

        // إن وُجد مسار في الستوريج استخدم Storage::url، وإلا صورة افتراضية لطيفة
        $avatar = $path
            ? \Illuminate\Support\Facades\Storage::url($path)
            : 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&background=E0F2FE&color=0F172A';

        return Inertia::render('Student/Dashboard', [
            'student' => [
                'name'   => $student->name,
                'avatar' => $avatar,
            ],
            'courses' => $courses,
        ]);
    }

    /**
     * صفحة تفاصيل المادة: معلومات + جدول + تنبيهات + تقرير حضور
     */
    public function showCourse(Course $course)
    {
        $student = Auth::user();

        // تأكيد التسجيل في المادة
        if (!$student->courses()->where('course_id', $course->id)->exists()) {
            abort(403);
        }

        // تحميل بيانات المادة والجداول
        $course->load('teacher', 'schedules');

        // جميع سجلات حضور هذا الطالب لهذه المادة عبر schedule_id
        $scheduleIds = $course->schedules->pluck('id');

        $attendanceRecords = $student->attendances()
            ->whereIn('schedule_id', $scheduleIds)
            ->orderBy('attendance_date', 'desc')
            ->get();

        // إحصائيات سريعة + تنبيه الغياب
        $total = $attendanceRecords->count();
        $present = $attendanceRecords->where('is_present', true)->count();
        $absent = $attendanceRecords->where('is_present', false)->count();
        $rate = $total > 0 ? round(($present / $total) * 100, 1) : 0;

        $alert = $absent >= 3
            ? "تنبيه: تم تسجيل {$absent} حالات غياب في هذه المادة."
            : null;

        return Inertia::render('Student/ShowCourse', [
            'course' => $course,
            'attendanceRecords' => $attendanceRecords,
            'stats' => [
                'total' => $total,
                'present' => $present,
                'absent' => $absent,
                'rate' => $rate,
            ],
            'alert' => $alert,
        ]);
    }
}
