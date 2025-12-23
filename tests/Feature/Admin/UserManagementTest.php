<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_view_users_index()
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertStatus(200);
    }

    /** @test */
    public function non_admin_cannot_access_users_index()
    {
        $user = User::factory()->student()->create();

        $this->actingAs($user)
            ->get(route('admin.users.index'))
            ->assertStatus(403);
    }

    /** @test */
    public function admin_can_create_user()
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name'                  => 'Test User',
                'email'                 => 'testuser@example.com',
                'role'                  => UserRole::TEACHER->value,
                'password'              => 'password',
                'password_confirmation' => 'password',
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
            'role'  => UserRole::TEACHER,
        ]);
    }

    /** @test */
    public function admin_can_update_user()
    {
        $admin = User::factory()->admin()->create();
        $user  = User::factory()->create();

        $this->actingAs($admin)
            ->put(route('admin.users.update', $user), [
                'name'                  => 'Updated Name',
                'email'                 => 'updated@example.com',
                'role'                  => UserRole::STUDENT->value,
                'password'              => '',
                'password_confirmation' => '',
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'id'    => $user->id,
            'name'  => 'Updated Name',
            'email' => 'updated@example.com',
            'role'  => UserRole::STUDENT,
        ]);
    }

    /** @test */
    public function admin_can_delete_user()
    {
        $admin = User::factory()->admin()->create();
        $user  = User::factory()->create();

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $user))
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
