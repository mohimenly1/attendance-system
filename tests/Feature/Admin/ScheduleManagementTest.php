<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Classroom;

class ScheduleManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_view_schedules_index()
    {
        $admin = User::factory()->admin()->create();

        $this
            ->actingAs($admin)
            ->get('/admin/schedules')
            ->assertStatus(200);
    }

    /** @test */
    public function non_admin_cannot_access_schedules_index()
    {
        $student = User::factory()->student()->create();

        $this
            ->actingAs($student)
            ->get('/admin/schedules')
            ->assertStatus(403);
    }

    /** @test */
    public function admin_can_create_schedule()
    {
        $admin     = User::factory()->admin()->create();
        $teacher   = User::factory()->teacher()->create();
        $course    = Course::factory()->create(['teacher_id' => $teacher->id]);
        $classroom = Classroom::factory()->create();

        $this
            ->actingAs($admin)
            ->post('/admin/schedules', [
                'course_id'    => $course->id,
                'teacher_id'   => $teacher->id,
                'classroom_id' => $classroom->id,
                'day_of_week'  => 'Monday',
                'start_time'   => '09:00',
                'end_time'     => '11:00',
                'is_active'    => true,
            ])
            ->assertRedirect(route('admin.schedules.index'));

        $this->assertDatabaseHas('schedules', [
            'course_id'   => $course->id,
            'day_of_week' => 'Monday',
            'start_time'  => '09:00:00',
            'end_time'    => '11:00:00',
        ]);
    }

    /** @test */
    public function admin_cannot_create_schedule_on_friday()
    {
        $admin   = User::factory()->admin()->create();
        $teacher = User::factory()->teacher()->create();
        $course  = Course::factory()->create(['teacher_id' => $teacher->id]);

        $this
            ->actingAs($admin)
            ->post('/admin/schedules', [
                'course_id'   => $course->id,
                'teacher_id'  => $teacher->id,
                'day_of_week' => 'Friday',
                'start_time'  => '09:00',
                'end_time'    => '10:00',
            ])
            ->assertSessionHasErrors('day_of_week');
    }

    /** @test */
    public function admin_can_update_schedule()
    {
        $admin   = User::factory()->admin()->create();
        $teacher = User::factory()->teacher()->create();
        $course  = Course::factory()->create(['teacher_id' => $teacher->id]);

        $schedule = Schedule::factory()->create([
            'course_id'   => $course->id,
            'teacher_id'  => $teacher->id,
            'day_of_week' => 'Sunday',
            'start_time'  => '08:00',
            'end_time'    => '10:00',
        ]);

        $this
            ->actingAs($admin)
            ->put("/admin/schedules/{$schedule->id}", [
                'course_id'   => $course->id,
                'teacher_id'  => $teacher->id,
                'day_of_week' => 'Tuesday',
                'start_time'  => '10:00',
                'end_time'    => '12:00',
                'is_active'   => true,
            ])
            ->assertRedirect(route('admin.schedules.index'));

        $this->assertDatabaseHas('schedules', [
            'id'          => $schedule->id,
            'day_of_week' => 'Tuesday',
            'start_time'  => '10:00:00',
        ]);
    }

    /** @test */
    public function admin_can_delete_schedule()
    {
        $admin    = User::factory()->admin()->create();
        $schedule = Schedule::factory()->create();

        $this
            ->actingAs($admin)
            ->delete("/admin/schedules/{$schedule->id}")
            ->assertRedirect(route('admin.schedules.index'));

        $this->assertDatabaseMissing('schedules', [
            'id' => $schedule->id,
        ]);
    }
}
