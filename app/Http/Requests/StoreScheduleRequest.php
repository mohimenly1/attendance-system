<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Course;
use App\Models\Schedule;

class StoreScheduleRequest extends FormRequest
{
    /**
     * تحديد إذا كان المستخدم مخوّل لإجراء الطلب.
     */
    public function authorize(): bool
    {
        // نجعلها true لأن الأدمن هو من يستخدم هذه الواجهة
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
            'classroom_id' => ['required', 'integer', Rule::exists('classrooms', 'id')],
            'day_of_week'  => ['required', Rule::in(['Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday'])],
            'start_time'   => ['required', 'date_format:H:i'],
            'end_time'     => ['required', 'date_format:H:i', 'after:start_time'],
            'notes'        => ['nullable', 'string'],
        ];
    }

    /**
     * التحقق الإضافي بعد الفاليديشن الأساسي.
     * يمنع التعارض في أوقات المحاضرات للمدرس أو الطلاب والقاعة.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $courseId = $this->input('course_id');
            $day      = $this->input('day_of_week');
            $start    = $this->input('start_time');
            $end      = $this->input('end_time');
            $teacher  = $this->input('teacher_id');   // قد يكون null
            $roomId   = $this->input('classroom_id'); // مطلوب في rules()

            // التحقق الأساسي
            if (! $courseId || ! $day || ! $start || ! $end) {
                return;
            }

            // الحصول على الدورة ومعها الطلاب
            $course = Course::with('students')->find($courseId);
            if (! $course) {
                $validator->errors()->add('course_id', 'The selected course was not found.');
                return;
            }

            // تحقق أن وقت البداية أصغر من النهاية (حماية إضافية بجانب after:start_time)
            if ($start >= $end) {
                $validator->errors()->add('start_time', 'Start time must be before end time.');
            }

            // المعلّم المفحوص: teacher_id من الطلب أو معلّم المادة
            $teacherId = $teacher ?: $course->teacher_id;

            // 1️⃣ فحص تعارض المعلّم (تداخل حقيقي: existing.start < new.end && existing.end > new.start)
            if ($teacherId && Schedule::hasOverlapForTeacher($teacherId, $day, $start, $end)) {
                $validator->errors()->add('teacher_id', 'This teacher already has a class at that time.');
            }

            // 2️⃣ فحص تعارض القاعة
            if ($roomId) {
                $roomBusy = Schedule::query()
                    ->where('classroom_id', $roomId)
                    ->where('day_of_week', $day)
                    ->where(function($q) use ($start, $end) {
                        $q->where('start_time', '<', $end)
                          ->where('end_time',   '>', $start);
                    })
                    ->exists();

                if ($roomBusy) {
                    $validator->errors()->add('classroom_id', 'This classroom is occupied at that time.');
                }
            }

            // 3️⃣ فحص تعارض الطلاب المسجلين
            foreach ($course->students as $student) {
                if (Schedule::hasOverlapForStudent($student->id, $day, $start, $end)) {
                    $validator->errors()->add('start_time', 'One or more students already have a class at that time.');
                    break; // نوقف عند أول تعارض
                }
            }
        });
    }
}
