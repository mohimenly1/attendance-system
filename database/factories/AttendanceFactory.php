<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\User;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        return [
            'student_id' => User::factory()->state([
                'role' => 'student',
            ]),
            'schedule_id' => Schedule::factory(),
            'is_present'  => true,
            'attendance_date' => now()->toDateString(),
        ];
    }
}
