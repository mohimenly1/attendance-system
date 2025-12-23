<?php

namespace Tests\Feature\Student;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentShowCourseTest extends TestCase
{
    use RefreshDatabase;

  /** @test */
    public function student_can_view_enrolled_course()
    {
        $student = User::factory()->create(['role' => 'student']);
        $teacher = User::factory()->create(['role' => 'teacher']);

        $course = Course::factory()->create([
            'teacher_id' => $teacher->id,
        ]);

        $course->students()->attach($student->id);

        $this->actingAs($student)
            ->get(route('student.courses.show', $course))
            ->assertStatus(200);
    }

  /** @test */
    public function student_cannot_view_course_not_enrolled()
    {
        $student = User::factory()->create(['role' => 'student']);
        $teacher = User::factory()->create(['role' => 'teacher']);

        $course = Course::factory()->create([
            'teacher_id' => $teacher->id,
        ]);

        $this->actingAs($student)
            ->get(route('student.courses.show', $course))
            ->assertStatus(403);
    }

    /** @test */
    public function attendance_alert_shows_only_after_more_than_3_absences_and_records_and_schedules_are_sent()
    {
        $student = User::factory()->create(['role' => 'student']);
        $teacher = User::factory()->create(['role' => 'teacher']);

        $course = Course::factory()->create([
            'teacher_id' => $teacher->id,
        ]);

        $course->students()->attach($student->id);

        // إنشاء جدول واحد
        $schedule = Schedule::factory()->create([
            'course_id'  => $course->id,
            'teacher_id' => $teacher->id,
            'day_of_week'=> 'Sunday',
            'start_time'=> '08:00',
            'end_time'  => '10:00',
        ]);

        // 4 غيابات بأيام مختلفة (مهم لتفادي UNIQUE constraint)
        foreach (range(1, 4) as $day) {
            Attendance::factory()->create([
                'student_id'      => $student->id,
                'schedule_id'     => $schedule->id,
                'attendance_date' => now()->subDays($day)->toDateString(),
                'is_present'      => false,
            ]);
        }

        $this->actingAs($student)
            ->get(route('student.courses.show', $course))
            ->assertStatus(200)
            ->assertInertia(fn ($page) =>
                $page
                    // ✅ اختبار أن الجداول مرسلة
                    ->has('course.schedules', 1)

                    // ✅ اختبار أن سجل الحضور مرسل
                    ->has('attendanceRecords', 4)

                    // ✅ اختبار الإحصائيات
                    ->where('stats.absent', 4)

                    // ✅ اختبار ظهور التنبيه
                    ->where('alert', fn ($alert) =>
                        str_contains($alert, 'غياب')
                    )
            );
    }
}
