<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'schedule_id', 'attendance_date', 'attended_at', 'is_present', 'departed_at' ];

    /**
     * تحويل الحقول تلقائياً إلى كائنات تاريخ (Carbon)
     */
    protected $casts = [
        'attendance_date' => 'date',      // إذا كان تاريخ فقط (بدون وقت)
        'attended_at'     => 'datetime',
        'departed_at'     => 'datetime',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];

    // --- العلاقات ---

    /**
     * علاقة "سجل الحضور يخص طالب واحد"
     * An Attendance belongs to a Student (User).
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * علاقة "سجل الحضور يخص محاضرة واحدة"
     * An Attendance belongs to a Schedule.
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
