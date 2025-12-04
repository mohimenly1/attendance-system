<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Course;
use App\Models\Classroom;
use App\Enums\UserRole;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1) Admin user
        User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@example.com',
            'password' => Hash::make('password'),
            'role'     => UserRole::ADMIN,
        ]);

        // 2) Teachers (5)
        $teachers = User::factory(5)->create([
            'role' => UserRole::TEACHER,
        ]);

        // 3) Students (50)
        $students = User::factory(50)->create([
            'role' => UserRole::STUDENT,
        ]);

       // ... سييدراتك الحالية
       $this->call(ClassroomSeeder::class);

        // 5) Courses (10) + تعيين teacher_id لكل مادة من قائمة المعلمين
        $courses = Course::factory(10)->create();

        // عيّن معلّم لكل مادة (عشوائي من الـ $teachers)
        $courses->each(function (Course $course) use ($teachers) {
            // لو الفاكتوري ما وضع teacher_id، نحدثه هنا
            if (empty($course->teacher_id)) {
                $course->update(['teacher_id' => $teachers->random()->id]);
            }
        });

        // 6) سجّل كل طالب في 3 مواد عشوائية (بدون تكرار)
        $students->each(function (User $student) use ($courses) {
            $student->courses()->syncWithoutDetaching(
                $courses->random(3)->pluck('id')->toArray()
            );
        });
    }
}
