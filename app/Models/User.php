<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\UserRole; // تأكد من إنشاء هذا الـ Enum

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class, // لتحويل النص إلى Enum تلقائياً
        ];
    }

    // --- العلاقات ---

    /**
     * علاقة "المعلم يدرّس عدة مواد"
     * A Teacher has many Courses.
     */
    public function teachingCourses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    /**
     * علاقة "الطالب مسجل في عدة مواد"
     * A Student belongs to many Courses.
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_student', 'student_id', 'course_id');
    }

    /**
     * علاقة "الطالب لديه سجلات حضور كثيرة"
     * A Student has many Attendances.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    /**
     * علاقة "الطالب لديه صورة واحدة"
     * A Student has one Photo.
     */
    public function photos() // Renamed to plural for clarity
    {
        return $this->hasMany(StudentPhoto::class, 'student_id');
    }

    /**
     * علاقة "المعلم لديه جداول تدريس (Schedules)"
     * A Teacher has many Schedules.
     */
    public function taughtSchedules()
    {
        return $this->hasMany(Schedule::class, 'teacher_id');
    }
}
