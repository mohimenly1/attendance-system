<?php

namespace App\Services;

use App\Models\Otp;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Support\Facades\Hash;

class OtpService
{
    /**
     * إنشاء OTP جديد مربوط بسجل حضور محدد
     */
    public function generate(User $student, Attendance $attendance): string
    {
        // حذف أي OTP سابق لنفس سجل الحضور
        Otp::where('attendance_id', $attendance->id)->delete();

        // توليد كود OTP من 6 أرقام
        $plainCode = random_int(100000, 999999);

        // تخزين الكود مشفّر مع وقت انتهاء
        Otp::create([
            'student_id'    => $student->id,
            'attendance_id' => $attendance->id,
            'code'          => Hash::make($plainCode),
            'expires_at'    => now()->addMinutes(config('otp.expires', 5)),
            'used'          => false,
        ]);

        return (string) $plainCode;
    }

    /**
     * التحقق من OTP
     */
    public function verify(User $student, Attendance $attendance, string $inputCode): bool
    {
        $otp = Otp::where('student_id', $student->id)
            ->where('attendance_id', $attendance->id)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            return false;
        }

        if (!Hash::check($inputCode, $otp->code)) {
            return false;
        }

        // تعليم OTP كمستخدم لمنع إعادة الاستخدام
        $otp->update(['used' => true]);

        return true;
    }
}
