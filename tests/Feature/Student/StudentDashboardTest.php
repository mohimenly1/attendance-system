<?php

namespace Tests\Feature\Student;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

class StudentDashboardTest extends TestCase
{
    use RefreshDatabase;

  /** @test */
    public function student_can_access_dashboard()
    {
        $student = User::factory()->create([
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($student)
            ->get(route('student.dashboard'));

        $response->assertStatus(200);

        $response->assertInertia(fn (Assert $page) =>
            $page->component('Student/Dashboard')
                 ->has('student.name')
                 ->has('student.avatar')
                 ->has('courses')
        );
    }

   /** @test */
    public function non_student_cannot_access_student_dashboard()
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($teacher)
            ->get(route('student.dashboard'))
            ->assertStatus(403);
    }

    /** @test */
    public function guest_is_redirected_to_login()
    {
        $this->get(route('student.dashboard'))
            ->assertRedirect('/login');
    }
}
