<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    /**
     * الأعمدة المسموح بالتعبئة
     */
    protected $fillable = [
        'name',
        'capacity',
    ];

    // --- العلاقات ---

    /**
     * علاقة "القاعة تحتوي على عدة محاضرات"
     * A Classroom has many Schedules.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'classroom_id');
    }
}
