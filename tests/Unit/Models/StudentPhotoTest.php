<?php

namespace Tests\Unit\Models;

use App\Models\StudentPhoto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentPhotoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_student_photo()
    {
        $student = User::factory()->create();

        $photo = StudentPhoto::create([
            'student_id' => $student->id,
            'photo_path' => 'photos/student1.jpg',
        ]);

        $this->assertDatabaseHas('student_photos', [
            'student_id' => $student->id,
            'photo_path' => 'photos/student1.jpg',
        ]);
    }

    /** @test */
    public function it_belongs_to_a_student()
    {
        $photo = StudentPhoto::factory()->create();

        $this->assertInstanceOf(User::class, $photo->student);
    }
}
