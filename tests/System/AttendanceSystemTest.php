<?php

namespace Tests\System;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Attendance;

class AttendanceSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_attendance_flow_via_face_recognition_or_otp()
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Fake Flask biometric service
        |--------------------------------------------------------------------------
        */
        Http::fake([
            'http://127.0.0.1:5000/recognize-face' =>
                Http::response([
                    'student_id' => 10,
                    'confidence' => 0.95,
                ], 200),
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2. Create users (teacher + student)
        |--------------------------------------------------------------------------
        */
        $teacher = User::factory()->teacher()->create([
            'email_verified_at' => now(),
        ]);

        $student = User::factory()->student()->create([
            'id' => 10,
        ]);

        /*
        |--------------------------------------------------------------------------
        | 3. Create course and enroll student
        |--------------------------------------------------------------------------
        */
        $course = Course::factory()->create([
            'teacher_id' => $teacher->id,
        ]);

        $course->students()->attach($student->id);

        /*
        |--------------------------------------------------------------------------
        | 4. Create schedule for today (mandatory for session start)
        |--------------------------------------------------------------------------
        */
        $schedule = Schedule::factory()->create([
            'course_id'   => $course->id,
            'day_of_week' => Carbon::now()->format('l'),
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5. Start attendance session
        |    (this is the ONLY place where Attendance rows are created)
        |--------------------------------------------------------------------------
        */
        $this->actingAs($teacher)
            ->get(route('teacher.attendance.start', $course))
            ->assertStatus(200);

        /*
        |--------------------------------------------------------------------------
        | 6. Send attendance image
        |--------------------------------------------------------------------------
        */
        $response = $this->post(
            route('teacher.attendance.mark'),
            [
                'image' => UploadedFile::fake()->create(
                    'face.jpg',
                    50,
                    'image/jpeg'
                ),
                'schedule_id' => $schedule->id,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 7. Assertions (SYSTEM-LEVEL, NOT FEATURE-LEVEL)
        |--------------------------------------------------------------------------
        */
        $response->assertStatus(200);

        $status = $response->json('status');

        // النظام مصمم ليقبل مسارين فقط
        $this->assertTrue(
            in_array($status, ['success', 'otp_required']),
            'Unexpected attendance status returned'
        );

        /*
        |--------------------------------------------------------------------------
        | 8. Database integrity checks
        |--------------------------------------------------------------------------
        */
        if ($status === 'success') {
            // في حال نجاح التعرّف البيومتري
            $this->assertDatabaseHas('attendances', [
                'student_id'      => $student->id,
                'schedule_id'     => $schedule->id,
                'attendance_date' => Carbon::today()->toDateString(),
                'is_present'      => true,
            ]);
        }

        if ($status === 'otp_required') {
            // في حال التحويل لمسار OTP
            $this->assertDatabaseHas('attendances', [
                'schedule_id'     => $schedule->id,
                'attendance_date' => Carbon::today()->toDateString(),
                'is_present'      => false,
            ]);
        }
    }
}
