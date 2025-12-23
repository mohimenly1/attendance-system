<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Otp extends Model
{
    use HasFactory;

    /**
     * الحقول القابلة للتعبئة الجماعية
     */
    protected $fillable = [
        'student_id',
        'attendance_id',
        'code',
        'expires_at',
        'used',
    ];

    /**
     * التحويلات (Casting)
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'used'       => 'boolean',
    ];

    /**
     * علاقة OTP مع الطالب
     * OTP belongs to User (Student)
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * علاقة OTP مع سجل الحضور
     * OTP belongs to Attendance
     */
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    /**
     * هل الـ OTP منتهي الصلاحية
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
