<?php

namespace Tests\Feature\Teacher;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\Schedule;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeacherCourseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function teacher_can_view_his_course_with_its_schedules()
    {
        // معلّم
        $teacher = User::factory()->teacher()->create();

        // مادة تخص هذا المعلّم
        $course = Course::factory()->create([
            'teacher_id' => $teacher->id,
            'name'       => 'Software Engineering',
        ]);

        // جدول محاضرة للمادة
        Schedule::factory()->create([
            'course_id'   => $course->id,
            'day_of_week' => 'Monday',
            'start_time'  => '09:00',
            'end_time'    => '11:00',
        ]);

        // تسجيل دخول المعلّم
        $this->actingAs($teacher);

        // فتح صفحة المادة
        $response = $this->get(route('teacher.courses.show', $course));

        // الصفحة تفتح
        $response->assertStatus(200);

        // اسم المادة ظاهر
        $response->assertSee('Software Engineering');

        // موعد المحاضرة ظاهر
        $response->assertSee('Monday');
        $response->assertSee('09:00');
        $response->assertSee('11:00');
    }

    /** @test */
    public function teacher_cannot_view_course_he_does_not_own()
    {
        // معلّم صاحب المادة
        $ownerTeacher = User::factory()->teacher()->create();

        // معلّم آخر
        $otherTeacher = User::factory()->teacher()->create();

        // مادة تخص المعلّم الأول
        $course = Course::factory()->create([
            'teacher_id' => $ownerTeacher->id,
        ]);

        // تسجيل دخول المعلّم الآخر
        $this->actingAs($otherTeacher);

        // محاولة الدخول
        $response = $this->get(route('teacher.courses.show', $course));

        // ممنوع
        $response->assertStatus(403);
    }
}
