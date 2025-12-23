<?php

namespace Tests\Unit\Models;

use App\Models\Classroom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClassroomTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_classroom()
    {
        // إنشاء صف باستخدام create() والتأكد من تخزينه في قاعدة البيانات
        $classroom = Classroom::create([
            'name' => 'Room A',
            'capacity' => 30,
        ]);

        $this->assertDatabaseHas('classrooms', [
            'name' => 'Room A',
            'capacity' => 30,
        ]);
    }

    /** @test */
    public function it_can_update_a_classroom()
    {
        // استخدام الفاكتوري لإنشاء صف
        $classroom = Classroom::factory()->create();

        // تحديث اسم الصف
        $classroom->update(['name' => 'Room B']);

        // التحقق من التحديث
        $this->assertDatabaseHas('classrooms', [
            'id' => $classroom->id,
            'name' => 'Room B',
        ]);
    }
}
