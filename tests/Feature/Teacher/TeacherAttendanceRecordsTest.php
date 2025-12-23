<?php

namespace Tests\Feature\Teacher;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class TeacherAttendanceRecordsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function teacher_can_view_attendance_records_for_his_course()
    {
        Carbon::setTestNow(Carbon::parse('Monday'));

        // معلّم
        $teacher = User::factory()->teacher()->create();

        // مادة يملكها
        $course = Course::factory()->create([
            'teacher_id' => $teacher->id,
        ]);

        // جدول للمادة
        $schedule = Schedule::factory()->create([
            'course_id'   => $course->id,
            'day_of_week' => 'Monday',
        ]);

        // طالب مسجّل في المادة
        $student = User::factory()->student()->create();
        $course->students()->attach($student);

        // سجل حضور صحيح (⚠️ course_id مطلوب)
        Attendance::create([
            'student_id'      => $student->id,
            'course_id'       => $course->id,
            'schedule_id'     => $schedule->id,
            'attendance_date' => Carbon::today(),
            'is_present'      => true,
        ]);

        // دخول المعلّم
        $response = $this->actingAs($teacher)
            ->get(route('teacher.courses.attendanceRecords', $course));

        $response->assertStatus(200);

        $response->assertInertia(fn ($page) =>
            $page
                ->component('Teacher/AttendanceRecords')
                ->has('course')
                ->has('attendanceRecords')
        );
    }

    /** @test */
    public function teacher_cannot_view_attendance_records_of_other_teachers_course()
    {
        $ownerTeacher = User::factory()->teacher()->create();
        $otherTeacher = User::factory()->teacher()->create();

        $course = Course::factory()->create([
            'teacher_id' => $ownerTeacher->id,
        ]);

        $this->actingAs($otherTeacher)
            ->get(route('teacher.courses.attendanceRecords', $course))
            ->assertStatus(403);
    }
}
