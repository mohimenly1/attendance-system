<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. إنشاء مستخدم مسؤول (Admin)
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN,
        ]);

        // 2. إنشاء 5 معلمين
        $teachers = User::factory(5)->create(['role' => UserRole::TEACHER]);

        // 3. إنشاء 50 طالب
        $students = User::factory(50)->create(['role' => UserRole::STUDENT]);

        // 4. إنشاء 10 مواد دراسية (بعد إنشاء المعلمين)
        $courses = Course::factory(10)->create();

        // 5. تسجيل كل طالب في 3 مواد بشكل عشوائي
        $students->each(function ($student) use ($courses) {
            $student->courses()->attach(
                $courses->random(3)->pluck('id')->toArray()
            );
        });
    }
}