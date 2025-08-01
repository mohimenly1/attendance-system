<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Enums\UserRole;

class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'code' => fake()->unique()->bothify('CS##??'), // مثال: CS45KL
            'description' => fake()->paragraph(),
            'teacher_id' => User::where('role', UserRole::TEACHER)->inRandomOrder()->first()->id,
        ];
    }
}