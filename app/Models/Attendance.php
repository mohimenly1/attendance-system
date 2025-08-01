<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'schedule_id', 'attendance_date', 'attended_at', 'is_present'];

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