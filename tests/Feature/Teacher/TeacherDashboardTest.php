<?php

namespace Tests\Feature\Teacher;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeacherDashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function teacher_can_access_dashboard()
    {
        $teacher = User::factory()->teacher()->create();

        Course::factory()->create([
            'teacher_id' => $teacher->id,
        ]);

        $response = $this->actingAs($teacher)
            ->get(route('teacher.dashboard'));

        $response->assertStatus(200);

        $response->assertInertia(fn ($page) =>
            $page
                ->component('Teacher/Dashboard')
                ->has('teacherName')
                ->has('courses')
                ->has('today')
        );
    }

    /** @test */
    public function non_teacher_is_blocked_from_dashboard()
    {
        $student = User::factory()->student()->create();

        $this->actingAs($student)
            ->get(route('teacher.dashboard'))
            ->assertStatus(403);
    }
}
