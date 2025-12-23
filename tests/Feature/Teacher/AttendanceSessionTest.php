<?php

namespace Tests\Feature\Teacher;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendanceSessionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function teacher_can_start_attendance_session_only_if_schedule_exists_for_today()
    {
        // تثبيت اليوم
        Carbon::setTestNow(Carbon::parse('Monday'));

        // إنشاء معلّم
        $teacher = User::factory()->teacher()->create();

        // إنشاء مادة تخص هذا المعلّم
        $course = Course::factory()->create([
            'teacher_id' => $teacher->id,
        ]);

        // إنشاء جدول للمادة في نفس اليوم
        $schedule = Schedule::factory()->create([
            'course_id'   => $course->id,
            'day_of_week' => 'Monday',
            'start_time'  => '09:00',
            'end_time'    => '11:00',
        ]);

        // طالب مسجل في المادة
        $student = User::factory()->student()->create();
        $course->students()->attach($student->id);

        // بدء جلسة الحضور
        $response = $this->actingAs($teacher)
            ->get(route('teacher.courses.attendance', $course));

        // الصفحة تفتح
        $response->assertStatus(200);

        // تم إنشاء سجل حضور مرتبط بالجدول واليوم
        $this->assertDatabaseHas('attendances', [
            'student_id'      => $student->id,
            'schedule_id'     => $schedule->id,
            'attendance_date' => Carbon::today()->toDateString(),
            'is_present'      => false,
        ]);
    }
}
