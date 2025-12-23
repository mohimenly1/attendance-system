<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OtpVerificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function student_can_verify_attendance_using_valid_otp()
    {
        // 1️⃣ إنشاء طالب وهمي
        $student = User::factory()->create([
            'role' => 'student',
        ]);

        // 2️⃣ إنشاء سجل حضور وهمي (غائب)
        $attendance = Attendance::factory()->create([
            'student_id' => $student->id,
            'is_present' => false,
            'attendance_date' => now()->toDateString(),
        ]);

        // 3️⃣ توليد OTP باستخدام الخدمة الحقيقية
        $otpService = app(OtpService::class);
        $plainOtp = $otpService->generate($student, $attendance);

        // 4️⃣ تنفيذ طلب التحقق (كأن الطالب أرسله)
        $response = $this
            ->actingAs($student)
            ->post('/student/attendance/verify-otp', [
                'attendance_id' => $attendance->id,
                'otp' => $plainOtp,
            ]);

        // 5️⃣ التحقق من الاستجابة
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'verified',
        ]);

        // 6️⃣ التأكد من تحديث سجل الحضور
        $this->assertDatabaseHas('attendances', [
            'id' => $attendance->id,
            'is_present' => true,
        ]);

        // 7️⃣ التأكد أن OTP تم استهلاكه
        $this->assertDatabaseHas('otps', [
            'attendance_id' => $attendance->id,
            'used' => true,
        ]);
    }
}
