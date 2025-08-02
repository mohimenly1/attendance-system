<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Course; // Import the Course model

class StudentController extends Controller
{
    public function dashboard()
    {
        $student = Auth::user();

        // Load the courses the student is enrolled in using the relationship
        // The 'courses' relationship was defined in the User model
        $courses = $student->courses()->with('teacher')->get();

        return Inertia::render('Student/Dashboard', [
            'courses' => $courses,
        ]);
    }

    public function showCourse(Course $course)
    {
        $student = Auth::user();

        // Ensure the student is actually enrolled in this course
        if (!$student->courses()->where('course_id', $course->id)->exists()) {
            abort(403);
        }

        // Load course schedules and the student's attendance for those schedules
        $course->load('teacher', 'schedules');

        $scheduleIds = $course->schedules->pluck('id');

        $attendanceRecords = $student->attendances()
                                     ->whereIn('schedule_id', $scheduleIds)
                                     ->orderBy('attendance_date', 'desc')
                                     ->get();

        return Inertia::render('Student/ShowCourse', [
            'course' => $course,
            'attendanceRecords' => $attendanceRecords,
        ]);
    }
}