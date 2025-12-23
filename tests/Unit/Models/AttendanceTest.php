<?php

namespace Tests\Unit\Models;

use App\Models\Attendance;
use App\Models\User;
use App\Models\Course;
use App\Models\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_an_attendance_record()
    {
        $attendance = Attendance::factory()->create();

        $this->assertDatabaseHas('attendances', [
            'id' => $attendance->id,
            'is_present' => 1,
        ]);
    }

    #[Test]
    public function attendance_belongs_to_student()
    {
        $attendance = Attendance::factory()->create();

        $this->assertInstanceOf(User::class, $attendance->student);
    }

    #[Test]
    public function attendance_belongs_to_course()
    {
        $attendance = Attendance::factory()->create();

        $this->assertInstanceOf(Course::class, $attendance->course);
    }

    #[Test]
    public function attendance_belongs_to_schedule()
    {
        $attendance = Attendance::factory()->create();

        $this->assertInstanceOf(Schedule::class, $attendance->schedule);
    }
}
