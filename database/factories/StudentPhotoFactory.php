<?php

namespace Database\Factories;

use App\Models\StudentPhoto;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentPhotoFactory extends Factory
{
    protected $model = StudentPhoto::class;

    public function definition(): array
    {
        return [
            'student_id' => User::factory(),
            'photo_path' => 'photos/student.jpg',
        ];
    }
}
