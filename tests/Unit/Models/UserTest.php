<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_user()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
        ]);
    }

    /** @test */
    public function it_can_update_a_user()
    {
        $user = User::factory()->create();

        $user->update(['name' => 'Updated User']);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated User',
        ]);
    }

    /** @test */
    public function it_can_delete_a_user()
    {
        $user = User::factory()->create();

        $user->delete();

        $this->assertDatabaseMissing('users', [
            'email' => $user->email,
        ]);
    }
}
