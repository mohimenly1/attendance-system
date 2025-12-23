<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_is_redirected_to_login_page()
    {
        $this->get('/dashboard')
            ->assertRedirect('/login');
    }

    /** @test */
    public function admin_is_redirected_to_admin_dashboard()
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/dashboard')
            ->assertRedirect(route('admin.dashboard'));
    }

    /** @test */
    public function teacher_is_redirected_to_teacher_dashboard()
    {
        $teacher = User::factory()->teacher()->create();

        $this->actingAs($teacher)
            ->get('/dashboard')
            ->assertRedirect(route('teacher.dashboard'));
    }

    /** @test */
    public function student_is_redirected_to_student_dashboard()
    {
        $student = User::factory()->student()->create();

        $this->actingAs($student)
            ->get('/dashboard')
            ->assertRedirect(route('student.dashboard'));
    }
}
