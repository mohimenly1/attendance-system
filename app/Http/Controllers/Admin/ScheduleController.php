<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Schedule;
use App\Models\Course;
use App\Models\User;
use App\Models\Classroom;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use Barryvdh\DomPDF\Facade\Pdf; // ✅ جديد: لاستخراج PDF

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // ✅ نقرأ الفلاتر القادمة من الواجهة (إن وُجدت)
        $filters = [
            'course_id' => $request->query('course_id', ''),
            'day'       => $request->query('day', ''),
            'search'    => $request->query('search', ''),
        ];

        $query = Schedule::with([
                'course:id,name,code,teacher_id',
                'course.teacher:id,name',
                'classroom:id,name,capacity',
                'teacher:id,name',
            ]);

        // ✅ تطبيق الفلاتر (اختياري)
        if (!empty($filters['course_id'])) {
            $query->where('course_id', $filters['course_id']);
        }

        if (!empty($filters['day'])) {
            $query->where('day_of_week', $filters['day']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('course', fn($cq) => $cq->where('name', 'like', "%{$search}%")
                                                   ->orWhere('code', 'like', "%{$search}%"))
                  ->orWhereHas('teacher', fn($tq) => $tq->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('classroom', fn($rq) => $rq->where('name', 'like', "%{$search}%"));
            });
        }

        $schedules = $query
            ->orderByRaw("FIELD(day_of_week,'Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday')")
            ->orderBy('start_time')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('admin/Schedules/Index', [
            'schedules' => $schedules,
            'filters'   => $filters, // ✅ لإعادة ملء حقول الفلاتر في الواجهة
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses    = Course::orderBy('name')->get(['id', 'name']);
        $teachers   = User::where('role','teacher')->orderBy('name')->get(['id','name']);
        $classrooms = Classroom::orderBy('name')->get(['id','name']);
        $days = ['Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday'];

        return Inertia::render('admin/Schedules/Create', [
            'courses'    => $courses,
            'teachers'   => $teachers,
            'classrooms' => $classrooms,
            'days'       => $days,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreScheduleRequest $request)
    {
        $data = $request->validated();

        // منع الجمعة
        if (($data['day_of_week'] ?? null) === 'Friday') {
            return back()->withErrors(['day_of_week' => 'لا توجد محاضرات يوم الجمعة.'])->withInput();
        }

        // منع التعارض على المعلّم
        $teacherConflict = Schedule::when(!empty($data['teacher_id']), fn($q) =>
                $q->where('teacher_id', $data['teacher_id'])
            )
            ->where('day_of_week', $data['day_of_week'] ?? '')
            ->where(function($q) use ($data) {
                $q->where('start_time', '<', $data['end_time'])
                  ->where('end_time',   '>', $data['start_time']);
            })
            ->exists();

        if ($teacherConflict) {
            return back()->withErrors(['teacher_id' => 'هذا المعلم لديه محاضرة أخرى في نفس اليوم والوقت.'])->withInput();
        }

        // منع التعارض على القاعة
        $roomConflict = Schedule::when(!empty($data['classroom_id']), fn($q) =>
                $q->where('classroom_id', $data['classroom_id'])
            )
            ->where('day_of_week', $data['day_of_week'] ?? '')
            ->where(function($q) use ($data) {
                $q->where('start_time', '<', $data['end_time'])
                  ->where('end_time',   '>', $data['start_time']);
            })
            ->exists();

        if ($roomConflict) {
            return back()->withErrors(['classroom_id' => 'هذه القاعة مشغولة في هذا الوقت.'])->withInput();
        }

        Schedule::create([
            'course_id'    => $data['course_id'],
            'teacher_id'   => $data['teacher_id']   ?? null,
            'classroom_id' => $data['classroom_id'] ?? null,
            'day_of_week'  => $data['day_of_week'],
            'start_time'   => $data['start_time'],
            'end_time'     => $data['end_time'],
            'notes'        => $data['notes'] ?? null,
            'is_active'    => $data['is_active'] ?? true,
        ]);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Schedule created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $schedule = Schedule::with([
                'course:id,name,code,teacher_id',
                'course.teacher:id,name',
                'classroom:id,name,capacity',
                'teacher:id,name',
            ])->findOrFail($id);

        return Inertia::render('admin/Schedules/Show', [
            'schedule' => $schedule,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $schedule = Schedule::with(['course','teacher','classroom'])->findOrFail($id);
        $courses    = Course::orderBy('name')->get(['id', 'name']);
        $teachers   = User::where('role','teacher')->orderBy('name')->get(['id','name']);
        $classrooms = Classroom::orderBy('name')->get(['id','name']);
        $days = ['Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday'];

        // ✅ نصيغ بيانات الجدول بشكل صريح قبل الإرسال
        $scheduleData = [
            'id'           => $schedule->id,
            'course_id'    => $schedule->course_id,
            'teacher_id'   => $schedule->teacher_id,
            'classroom_id' => $schedule->classroom_id,
            'day_of_week'  => $schedule->day_of_week,
            'start_time'   => substr((string)$schedule->start_time, 0, 5),
            'end_time'     => substr((string)$schedule->end_time, 0, 5),
            'notes'        => $schedule->notes, // ✅ هنا بيت الملاحظة
            'is_active'    => $schedule->is_active,
        ];

        return Inertia::render('admin/Schedules/Edit', [
            'schedule'   => $scheduleData,
            'courses'    => $courses,
            'teachers'   => $teachers,
            'classrooms' => $classrooms,
            'days'       => $days,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScheduleRequest $request, string $id)
    {
        $schedule = Schedule::findOrFail($id);
        $data = $request->validated();

        // منع الجمعة
        if (($data['day_of_week'] ?? null) === 'Friday') {
            return back()->withErrors(['day_of_week' => 'لا توجد محاضرات يوم الجمعة.'])->withInput();
        }

        // منع التعارض على المعلّم (مع استثناء السجل الحالي)
        $teacherConflict = Schedule::where('id','<>',$schedule->id)
            ->when(!empty($data['teacher_id']), fn($q) => $q->where('teacher_id', $data['teacher_id']))
            ->where('day_of_week', $data['day_of_week'] ?? '')
            ->where(function($q) use ($data) {
                $q->where('start_time', '<', $data['end_time'])
                  ->where('end_time',   '>', $data['start_time']);
            })
            ->exists();

        if ($teacherConflict) {
            return back()->withErrors(['teacher_id' => 'هذا المعلم لديه محاضرة أخرى في نفس اليوم والوقت.'])->withInput();
        }

        // منع التعارض على القاعة (مع استثناء السجل الحالي)
        $roomConflict = Schedule::where('id','<>',$schedule->id)
            ->when(!empty($data['classroom_id']), fn($q) => $q->where('classroom_id', $data['classroom_id']))
            ->where('day_of_week', $data['day_of_week'] ?? '')
            ->where(function($q) use ($data) {
                $q->where('start_time', '<', $data['end_time'])
                  ->where('end_time',   '>', $data['start_time']);
            })
            ->exists();

        if ($roomConflict) {
            return back()->withErrors(['classroom_id' => 'هذه القاعة مشغولة في هذا الوقت.'])->withInput();
        }

        $schedule->update([
            'course_id'    => $data['course_id'],
            'teacher_id'   => $data['teacher_id']   ?? null,
            'classroom_id' => $data['classroom_id'] ?? null,
            'day_of_week'  => $data['day_of_week'],
            'start_time'   => $data['start_time'],
            'end_time'     => $data['end_time'],
            'notes'        => $data['notes'] ?? null,
            'is_active'    => $data['is_active'] ?? $schedule->is_active,
        ]);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Schedule updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Schedule deleted.');
    }

    /**
     * Export filtered schedules as CSV.
     */
    public function export(Request $request)
    {
        // نفس منطق الفلاتر المستخدم في index
        $filters = [
            'course_id' => $request->query('course_id', ''),
            'day'       => $request->query('day', ''),
            'search'    => $request->query('search', ''),
        ];

        $query = Schedule::with(['course','teacher','classroom']);

        if (!empty($filters['course_id'])) {
            $query->where('course_id', $filters['course_id']);
        }

        if (!empty($filters['day'])) {
            $query->where('day_of_week', $filters['day']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('course', fn($cq) => $cq->where('name', 'like', "%{$search}%")
                                                   ->orWhere('code', 'like', "%{$search}%"))
                  ->orWhereHas('teacher', fn($tq) => $tq->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('classroom', fn($rq) => $rq->where('name', 'like', "%{$search}%"));
            });
        }

        $schedules = $query
            ->orderByRaw("FIELD(day_of_week,'Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday')")
            ->orderBy('start_time')
            ->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="schedules.csv"',
            'Cache-Control'       => 'no-store, no-cache, must-revalidate',
            'Pragma'              => 'no-cache',
        ];

        $columns = ['Course','Teacher','Classroom','Day','Start','End','Notes','Active'];

        $callback = function () use ($schedules, $columns) {
            $out = fopen('php://output', 'w');
            // BOM للغة العربية
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($out, $columns);

            foreach ($schedules as $s) {
                fputcsv($out, [
                    optional($s->course)->name,
                    optional($s->teacher)->name,
                    optional($s->classroom)->name,
                    $s->day_of_week,
                    (string)$s->start_time,
                    (string)$s->end_time,
                    $s->notes,
                    $s->is_active ? '1' : '0',
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export filtered schedules as PDF.
     */
    public function exportPdf(Request $request)
    {
        // نفس الفلاتر
        $filters = [
            'course_id' => $request->query('course_id', ''),
            'day'       => $request->query('day', ''),
            'search'    => $request->query('search', ''),
        ];

        $query = Schedule::with(['course','teacher','classroom']);

        if (!empty($filters['course_id'])) {
            $query->where('course_id', $filters['course_id']);
        }
        if (!empty($filters['day'])) {
            $query->where('day_of_week', $filters['day']);
        }
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('course', fn($cq) => $cq->where('name', 'like', "%{$search}%")
                                                   ->orWhere('code', 'like', "%{$search}%"))
                  ->orWhereHas('teacher', fn($tq) => $tq->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('classroom', fn($rq) => $rq->where('name', 'like', "%{$search}%"));
            });
        }

        $schedules = $query
            ->orderByRaw("FIELD(day_of_week,'Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday')")
            ->orderBy('start_time')
            ->get();

        // استخدام Dompdf عبر الواجهة (Facade)
        Pdf::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled'      => true,
        ]);

        $pdf = Pdf::loadView('admin.schedules.pdf', ['schedules' => $schedules])
                  ->setPaper('a4', 'portrait');

        return $pdf->download('schedules.pdf');
    }
}
