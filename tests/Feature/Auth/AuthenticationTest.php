<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_login_page()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);

        // النظام يعتمد توجيه مبني على الدور (طالب افتراضي)
        $response->assertRedirect('/student/dashboard');
    }

    /** @test */
    public function user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function authenticated_user_can_logout()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    /** @test */
    public function unauthenticated_user_cannot_access_protected_pages()
    {
        $response = $this->get('/student/dashboard');

        $response->assertRedirect('/login');
    }
}
