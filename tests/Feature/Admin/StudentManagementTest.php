<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\StudentPhoto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StudentManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_view_students_index()
    {
        $admin = User::factory()->admin()->create();

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.students.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function non_admin_cannot_access_students_index()
    {
        $student = User::factory()->student()->create();

        $response = $this
            ->actingAs($student)
            ->get(route('admin.students.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_create_student_with_photos()
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.students.store'), [
                'name'                  => 'Test Student',
                'email'                 => 'student@test.com',
                'password'              => 'password123',
                'password_confirmation' => 'password123',
                'photos' => [
                    UploadedFile::fake()->create('photo1.jpg', 100, 'image/jpeg'),
                    UploadedFile::fake()->create('photo2.jpg', 100, 'image/jpeg'),
                ],
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'email' => 'student@test.com',
            'role'  => 'student',
        ]);

        $this->assertDatabaseCount('student_photos', 2);
    }

    /** @test */
    public function admin_can_update_student()
    {
        Storage::fake('public');

        $admin   = User::factory()->admin()->create();
        $student = User::factory()->student()->create();

        $response = $this
            ->actingAs($admin)
            ->put(route('admin.students.update', $student), [
                'name'                  => 'Updated Name',
                'email'                 => 'updated@test.com',
                'password'              => 'newpassword123',
                'password_confirmation' => 'newpassword123',
                'photos' => [
                    UploadedFile::fake()->create('new.jpg', 100, 'image/jpeg'),
                ],
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id'    => $student->id,
            'name'  => 'Updated Name',
            'email' => 'updated@test.com',
        ]);

        $this->assertDatabaseCount('student_photos', 1);
    }

    /** @test */
    public function admin_can_delete_student_and_photos()
    {
        Storage::fake('public');

        $admin   = User::factory()->admin()->create();
        $student = User::factory()->student()->create();

        $photo = StudentPhoto::create([
            'student_id' => $student->id,
            'photo_path' => 'student_photos/test.jpg',
        ]);

        Storage::disk('public')->put($photo->photo_path, 'dummy');

        $response = $this
            ->actingAs($admin)
            ->delete(route('admin.students.destroy', $student));

        $response->assertRedirect();

        $this->assertDatabaseMissing('users', [
            'id' => $student->id,
        ]);

        $this->assertDatabaseMissing('student_photos', [
            'id' => $photo->id,
        ]);

        Storage::disk('public')->assertMissing($photo->photo_path);
    }
}
