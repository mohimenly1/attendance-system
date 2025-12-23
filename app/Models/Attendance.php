<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'schedule_id',
        'attendance_date',
        'course_id',
        'attended_at',
        'departed_at',
        'is_present',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'attended_at'     => 'datetime',
        'departed_at'     => 'datetime',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    /**
     * الوصول للمادة عبر الجدول
     * Attendance → Schedule → Course
     */
    public function course()
    {
        return $this->hasOneThrough(
            Course::class,
            Schedule::class,
            'id',        // schedules.id
            'id',        // courses.id
            'schedule_id',
            'course_id'
        );
    }
}
