<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Services\OtpService;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    /**
     * التحقق من OTP وتسجيل الحضور
     */
    public function verify(Request $request)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
            'otp'           => 'required|string',
        ]);

        // المستخدم الحالي (طالب)
        $student = Auth::user();

        // سجل الحضور
        $attendance = Attendance::findOrFail($request->attendance_id);

        // التحقق من أن السجل يخص نفس الطالب
        if ($attendance->student_id !== $student->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // التحقق من OTP
        $isValid = app(OtpService::class)->verify(
            $student,
            $attendance,
            $request->otp
        );

        if (!$isValid) {
            return response()->json([
                'error' => 'Invalid or expired OTP'
            ], 422);
        }

        // تسجيل الحضور بعد نجاح التحقق
        $attendance->update([
            'is_present'  => true,
            'attended_at' => now(),
        ]);

        return response()->json([
            'status'  => 'verified',
            'message' => 'Attendance verified successfully'
        ]);
    }
}
