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
        // عدّل هذه السطر إذا لديك سياسات صلاحية محددة
        return true;
    }

    /**
     * قواعد الفاليديشن الأساسية.
     */
    public function rules(): array
    {
        return [
            'course_id'    => ['required', 'integer', Rule::exists('courses', 'id')],
            'teacher_id'   => ['nullable', 'integer', Rule::exists('users', 'id')],
            'classroom_id' => ['required', 'integer', Rule::exists('classrooms', 'id')],
            'day_of_week'  => ['required', Rule::in(['Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday'])],
            'start_time'   => ['required', 'date_format:H:i'],
            'end_time'     => ['required', 'date_format:H:i', 'after:start_time'],
            'notes'        => ['nullable', 'string'],
        ];
    }

    /**
     * التحقق الإضافي بعد الفاليديشن الأساسي.
     * يجنّب التعارض مع سجلات أخرى مع استثناء السجل الحالي.
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

            // استخرج id السجل الجاري تحديثه من route parameter (resource route: {schedule})
            $excludeId = null;
            $routeParam = $this->route('schedule');
            if ($routeParam) {
                $excludeId = is_object($routeParam) ? ($routeParam->id ?? null) : $routeParam;
            }
            // كخيار بديل، إن مررت id في body
            if (! $excludeId && $this->input('id')) {
                $excludeId = $this->input('id');
            }

            // تحقق أساسي من القيم
            if (! $courseId || ! $day || ! $start || ! $end) {
                return;
            }

            // الحصول على الدورة ومعها الطلاب
            $course = Course::with('students')->find($courseId);

            if (! $course) {
                $validator->errors()->add('course_id', 'The selected course was not found.');
                return;
            }

            // تحقق أن وقت البداية أصغر من النهاية (تحقق إضافي بجانب after:start_time)
            if ($start >= $end) {
                $validator->errors()->add('start_time', 'Start time must be before end time.');
            }

            // 1) فحص تعارض المعلّم (نستخدم teacher_id من الطلب، وإن لم يوجد نرجع لمعلّم المادة) مع استثناء السجل الحالي
            $teacherId = $teacher ?: $course->teacher_id;
            if ($teacherId && Schedule::hasOverlapForTeacher($teacherId, $day, $start, $end, $excludeId)) {
                $validator->errors()->add('teacher_id', 'This teacher already has another class at that time.');
            }

            // 2) فحص تعارض القاعة مع استثناء السجل الحالي
            if ($roomId) {
                $roomBusy = Schedule::query()
                    ->where('id', '<>', $excludeId)
                    ->where('classroom_id', $roomId)
                    ->where('day_of_week', $day)
                    ->where(function($q) use ($start, $end) {
                        // تداخل حقيقي: existing.start < new.end && existing.end > new.start
                        $q->where('start_time', '<', $end)
                          ->where('end_time',   '>', $start);
                    })
                    ->exists();

                if ($roomBusy) {
                    $validator->errors()->add('classroom_id', 'This classroom is occupied at that time.');
                }
            }

            // 3) فحص تعارض الطلاب المسجلين مع استثناء السجل الحالي
            foreach ($course->students as $student) {
                if (Schedule::hasOverlapForStudent($student->id, $day, $start, $end, $excludeId)) {
                    $validator->errors()->add('start_time', 'One or more students already have a class at that time.');
                    break;
                }
            }
        });
    }
}
