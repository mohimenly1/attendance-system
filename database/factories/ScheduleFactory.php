<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition(): array
    {
        return [
            'course_id'   => Course::factory(),
            'day_of_week' => $this->faker->randomElement([
                'Sunday',
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
            ]),
            'start_time' => '08:00',
            'end_time'   => '10:00',
        ];
    }
}
