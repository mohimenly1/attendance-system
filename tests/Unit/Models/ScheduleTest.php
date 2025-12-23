<?php

namespace Tests\Unit\Models;

use App\Models\Schedule;
use App\Models\Course;
use App\Models\User;
use App\Models\Classroom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScheduleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * اختبار إنشاء سجل جديد في جدول schedules.
     *
     * @test
     */
    public function it_can_create_a_schedule()
    {
        // إعداد البيانات
        $course = Course::factory()->create();
        $teacher = User::factory()->create();
        $classroom = Classroom::factory()->create();

        // إنشاء جدول جديد
        $schedule = Schedule::create([
            'course_id' => $course->id,
            'teacher_id' => $teacher->id,
            'classroom_id' => $classroom->id,
            'day_of_week' => 'Monday',
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'is_active' => true,
        ]);

        // التحقق من أنه تم إنشاء الجدول بنجاح
        $this->assertDatabaseHas('schedules', [
            'course_id' => $course->id,
            'teacher_id' => $teacher->id,
            'classroom_id' => $classroom->id,
            'day_of_week' => 'Monday',
        ]);
    }

    /**
     * اختبار العلاقة مع المادة (Course).
     *
     * @test
     */
    public function it_belongs_to_a_course()
    {
        $schedule = Schedule::factory()->create();

        // تحقق من أن العلاقة مع `course` صحيحة
        $this->assertInstanceOf(Course::class, $schedule->course);
    }

    /**
     * اختبار العلاقة مع المدرس (Teacher).
     *
     * @test
     */
    public function it_belongs_to_a_teacher()
    {
        $schedule = Schedule::factory()->create();

        // تحقق من أن العلاقة مع `teacher` صحيحة
        $this->assertInstanceOf(User::class, $schedule->teacher);
    }

    /**
     * اختبار العلاقة مع القاعة الدراسية (Classroom).
     *
     * @test
     */
    public function it_belongs_to_a_classroom()
    {
        $schedule = Schedule::factory()->create();

        // تحقق من أن العلاقة مع `classroom` صحيحة
        $this->assertInstanceOf(Classroom::class, $schedule->classroom);
    }

    /**
     * اختبار تحديث سجل في جدول schedules.
     *
     * @test
     */
    public function it_can_update_a_schedule()
    {
        // إنشاء جدول جديد
        $schedule = Schedule::factory()->create();

        // تحديث بيانات الجدول
        $schedule->update([
            'day_of_week' => 'Tuesday',
        ]);

        // التحقق من التحديث في قاعدة البيانات
        $this->assertDatabaseHas('schedules', [
            'id' => $schedule->id,
            'day_of_week' => 'Tuesday',
        ]);
    }

    /**
     * اختبار حذف سجل من جدول schedules.
     *
     * @test
     */
    public function it_can_delete_a_schedule()
    {
        // إنشاء جدول جديد
        $schedule = Schedule::factory()->create();

        // حذف الجدول
        $schedule->delete();

        // التحقق من أن الجدول تم حذفه من قاعدة البيانات
        $this->assertDatabaseMissing('schedules', [
            'id' => $schedule->id,
        ]);
    }

    /**
     * اختبار حالة التفعيل (Active) لجدول schedules.
     *
     * @test
     */
    public function it_can_activate_a_schedule()
    {
        $schedule = Schedule::factory()->create([
            'is_active' => false,
        ]);

        // تحديث حالة التفعيل
        $schedule->update([
            'is_active' => true,
        ]);

        // التحقق من أن `is_active` تم تحديثها إلى true
        $this->assertTrue($schedule->is_active);
    }
}
