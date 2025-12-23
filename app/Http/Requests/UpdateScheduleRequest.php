<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Course;
use App\Models\Schedule;

class UpdateScheduleRequest extends FormRequest
{
    /**
     * تحديد إذا كان المستخدم مخوّل لإجراء الطلب.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * قواعد التحقق الأساسية.
     */
    public function rules(): array
    {
        return [
            'course_id'    => ['required', 'integer', Rule::exists('courses', 'id')],
            'teacher_id'   => ['nullable', 'integer', Rule::exists('users', 'id')],
            'classroom_id' => ['nullable', 'integer', Rule::exists('classrooms', 'id')], // ✔ التصحيح هنا
            'day_of_week'  => ['required', Rule::in(['Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday'])],
            'start_time'   => ['required', 'date_format:H:i'],
            'end_time'     => ['required', 'date_format:H:i', 'after:start_time'],
            'notes'        => ['nullable', 'string'],
            'is_active'    => ['nullable', 'boolean'],
        ];
    }

    /**
     * تحقق إضافي لمنع التعارضات مع استثناء السجل الحالي.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $courseId = $this->input('course_id');
            $day      = $this->input('day_of_week');
            $start    = $this->input('start_time');
            $end      = $this->input('end_time');
            $teacher  = $this->input('teacher_id');
            $roomId   = $this->input('classroom_id');

            // استخراج ID السجل الحالي من الراوت
            $excludeId = null;
            $routeParam = $this->route('schedule');
            if ($routeParam) {
                $excludeId = is_object($routeParam)
                    ? ($routeParam->id ?? null)
                    : $routeParam;
            }

            if (! $courseId || ! $day || ! $start || ! $end) {
                return;
            }

            $course = Course::with('students')->find($courseId);

            if (! $course) {
                $validator->errors()->add('course_id', 'The selected course was not found.');
                return;
            }

            // تحقق إضافي للوقت
            if ($start >= $end) {
                $validator->errors()->add('start_time', 'Start time must be before end time.');
            }

            // تعارض المعلّم
            $teacherId = $teacher ?: $course->teacher_id;
            if ($teacherId && Schedule::hasOverlapForTeacher($teacherId, $day, $start, $end, $excludeId)) {
                $validator->errors()->add('teacher_id', 'This teacher already has another class at that time.');
            }

            // تعارض القاعة (اختياري)
            if ($roomId && Schedule::query()
                ->where('id', '<>', $excludeId)
                ->where('classroom_id', $roomId)
                ->where('day_of_week', $day)
                ->where(function ($q) use ($start, $end) {
                    $q->where('start_time', '<', $end)
                      ->where('end_time',   '>', $start);
                })
                ->exists()
            ) {
                $validator->errors()->add('classroom_id', 'This classroom is occupied at that time.');
            }

            // تعارض الطلاب
            foreach ($course->students as $student) {
                if (Schedule::hasOverlapForStudent($student->id, $day, $start, $end, $excludeId)) {
                    $validator->errors()->add(
                        'start_time',
                        'One or more students already have a class at that time.'
                    );
                    break;
                }
            }
        });
    }
}
