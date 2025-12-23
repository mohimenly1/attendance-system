<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'name'        => $this->faker->sentence(3),
            'code'        => $this->faker->unique()->bothify('CS###'),
            'description' => $this->faker->paragraph(),
            'teacher_id'  => User::factory()->teacher(),
        ];
    }
}
