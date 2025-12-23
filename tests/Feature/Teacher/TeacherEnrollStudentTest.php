<?php

namespace Tests\Feature\Teacher;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeacherEnrollStudentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function teacher_can_enroll_student_in_his_course()
    {
        // معلّم
        $teacher = User::factory()->teacher()->create();

        // مادة للمعلّم
        $course = Course::factory()->create([
            'teacher_id' => $teacher->id,
        ]);

        // طالب موجود
        $student = User::factory()->student()->create();

        // تسجيل دخول المعلّم
        $response = $this->actingAs($teacher)->post(
            route('teacher.courses.enroll', $course),
            [
                'student_id' => $student->id,
            ]
        );

        // يرجع لصفحة المادة
        $response->assertRedirect(route('teacher.courses.show', $course));

        // الطالب تم ربطه بالمادة
        $this->assertDatabaseHas('course_student', [
            'course_id'  => $course->id,
            'student_id' => $student->id,
        ]);
    }

    /** @test */
    public function teacher_cannot_enroll_student_in_course_he_does_not_own()
    {
        $ownerTeacher = User::factory()->teacher()->create();
        $otherTeacher = User::factory()->teacher()->create();

        $course = Course::factory()->create([
            'teacher_id' => $ownerTeacher->id,
        ]);

        $student = User::factory()->student()->create();

        $this->actingAs($otherTeacher)
            ->post(route('teacher.courses.enroll', $course), [
                'student_id' => $student->id,
            ])
            ->assertStatus(403);
    }
}
