<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'description', 'teacher_id'];

    // --- العلاقات ---

    /**
     * علاقة "المادة تتبع لمعلم واحد"
     * A Course belongs to a Teacher (User).
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * علاقة "المادة بها عدة طلاب"
     * A Course has many Students (Users).
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'course_student', 'course_id', 'student_id');
    }

    /**
     * علاقة "المادة لها عدة مواعيد محاضرات"
     * A Course has many Schedules.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * علاقة "المادة لها سجلات حضور كثيرة"
     * A Course has many Attendance records.
     */
   public function attendances()
{
    return $this->hasManyThrough(Attendance::class, Schedule::class);
}
}
