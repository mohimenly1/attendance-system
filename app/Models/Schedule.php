<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'day_of_week', 'start_time', 'end_time'];

    // --- العلاقات ---

    /**
     * علاقة "المحاضرة تتبع لمادة واحدة"
     * A Schedule belongs to a Course.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * علاقة "المحاضرة لها سجلات حضور كثيرة"
     * A Schedule has many Attendances.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}