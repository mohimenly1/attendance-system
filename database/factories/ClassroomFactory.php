<?php

namespace Database\Factories;

use App\Models\Classroom;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassroomFactory extends Factory
{
    protected $model = Classroom::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word, // مثال: "Room A"
            'capacity' => $this->faker->numberBetween(20, 50), // تحديد سعة عشوائية بين 20 و 50
        ];
    }
}
