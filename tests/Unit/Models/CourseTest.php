<?php

namespace Tests\Unit\Models;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_course()
    {
        // إنشاء معلم باستخدام الفاكتوري
        $teacher = User::factory()->teacher()->create(); // التأكد من أن الفاكتوري يخلق مدرسًا

        // إنشاء مادة باستخدام الفاكتوري مع إضافة الكود
        $course = Course::create([
            'name' => 'Math 101',
            'code' => 'CS101',  // إضافة الكود هنا
            'teacher_id' => $teacher->id,
        ]);

        // التحقق من أن المادة تم إنشاؤها بنجاح
        $this->assertDatabaseHas('courses', [
            'name' => 'Math 101',
            'code' => 'CS101',  // التحقق من الكود
            'teacher_id' => $teacher->id,
        ]);
    }

    /** @test */
    public function it_belongs_to_a_teacher()
    {
        // إنشاء مادة باستخدام الفاكتوري
        $course = Course::factory()->create();

        // التحقق من العلاقة بين المادة والمدرس
        $this->assertInstanceOf(User::class, $course->teacher);
    }

    /** @test */
    public function it_can_update_a_course()
    {
        // إنشاء معلم واستخدام الفاكتوري
        $teacher = User::factory()->teacher()->create();

        // إنشاء مادة
        $course = Course::factory()->create([
            'teacher_id' => $teacher->id,
        ]);

        // تحديث اسم المادة
        $course->update([
            'name' => 'Updated Math 101',
        ]);

        // التحقق من أن المادة تم تحديثها بنجاح
        $this->assertDatabaseHas('courses', [
            'name' => 'Updated Math 101',
            'teacher_id' => $teacher->id,
        ]);
    }

    /** @test */
    public function it_can_delete_a_course()
    {
        // إنشاء معلم واستخدام الفاكتوري
        $teacher = User::factory()->teacher()->create();

        // إنشاء مادة
        $course = Course::factory()->create([
            'teacher_id' => $teacher->id,
        ]);

        // حذف المادة
        $course->delete();

        // التحقق من أن المادة تم حذفها
        $this->assertDatabaseMissing('courses', [
            'name' => $course->name,
            'teacher_id' => $teacher->id,
        ]);
    }
}
