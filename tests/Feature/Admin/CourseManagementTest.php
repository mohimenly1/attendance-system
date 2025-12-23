<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_view_courses_page()
    {
        $admin = User::factory()->admin()->create();

        $this
            ->actingAs($admin)
            ->get('/admin/courses')
            ->assertStatus(200);
    }

    /** @test */
    public function non_admin_cannot_access_courses_page()
    {
        $student = User::factory()->student()->create();

        $this
            ->actingAs($student)
            ->get('/admin/courses')
            ->assertStatus(403);
    }

    /** @test */
    public function admin_can_create_course()
    {
        $admin   = User::factory()->admin()->create();
        $teacher = User::factory()->teacher()->create();

        $this
            ->actingAs($admin)
            ->post('/admin/courses', [
                'name'        => 'Software Engineering',
                'code'        => 'SE101',
                'description' => 'Core course',
                'teacher_id'  => $teacher->id,
            ])
            ->assertStatus(302);

        $this->assertDatabaseHas('courses', [
            'code' => 'SE101',
        ]);
    }

    /** @test */
    public function admin_can_update_course()
    {
        $admin   = User::factory()->admin()->create();
        $course  = Course::factory()->create();

        $this
            ->actingAs($admin)
            ->put("/admin/courses/{$course->id}", [
                'name'        => 'Updated Course Name',
                'code'        => $course->code,
                'description' => 'Updated description',
                'teacher_id'  => $course->teacher_id,
            ])
            ->assertStatus(302);

        $this->assertDatabaseHas('courses', [
            'id'   => $course->id,
            'name' => 'Updated Course Name',
        ]);
    }

    /** @test */
    public function admin_can_delete_course()
    {
        $admin  = User::factory()->admin()->create();
        $course = Course::factory()->create();

        $this
            ->actingAs($admin)
            ->delete("/admin/courses/{$course->id}")
            ->assertStatus(302);

        $this->assertDatabaseMissing('courses', [
            'id' => $course->id,
        ]);
    }
}

