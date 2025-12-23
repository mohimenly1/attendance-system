<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function password_confirmation_screen_can_be_rendered()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/confirm-password');

        $response->assertStatus(200);
    }

    /** @test */
    public function password_can_be_confirmed()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user);

        $response = $this->post('/confirm-password', [
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function password_is_not_confirmed_with_invalid_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user);

        $response = $this->post('/confirm-password', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    }
}
