<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'photo_path'];

    // --- العلاقات ---

    /**
     * علاقة "الصورة تتبع لطالب واحد"
     * A Photo belongs to a Student (User).
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}