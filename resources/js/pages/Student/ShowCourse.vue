<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
  course: { type: Object, default: () => ({}) },
  attendanceRecords: { type: Array, default: () => [] },
  stats: { type: Object, default: () => ({ total: 0, present: 0, absent: 0, rate: 0 }) },
  alert: { type: String, default: '' },
});

const courseName  = computed(() => props.course?.name ?? 'Course');
const courseCode  = computed(() => props.course?.code ?? '');
const teacherName = computed(() => props.course?.teacher?.name ?? '—');
const schedules   = computed(() => props.course?.schedules ?? []);
const s           = computed(() => ({
  total:  props.stats?.total  ?? 0,
  present:props.stats?.present?? 0,
  absent: props.stats?.absent ?? 0,
  rate:   props.stats?.rate   ?? 0,
}));

function fmtDate(d) {
  if (!d) return '—';
  const date = new Date(d); if (isNaN(date)) return String(d);
  const y = date.getFullYear(), m = String(date.getMonth()+1).padStart(2,'0'), day = String(date.getDate()).padStart(2,'0');
  return `${y}-${m}-${day}`;
}
function fmtTime(t) {
  if (!t) return '—';
  const date = new Date(t); if (isNaN(date)) return String(t);
  const hh = String(date.getHours()).padStart(2,'0'), mm = String(date.getMinutes()).padStart(2,'0');
  return `${hh}:${mm}`;
}
</script>

<template>
  <Head :title="courseName" />

  <AuthenticatedLayout>
    <!-- 🔹 رأس الصفحة الجديد (اسم المادة بشكل مميز) -->
    <template #header>
      <div class="flex items-center gap-3">
        <!-- أيقونة بتدرّج -->
        <div
          class="h-9 w-9 rounded-xl bg-gradient-to-br from-sky-400 to-indigo-500 shadow-sm flex items-center justify-center"
        >
          <span class="text-white text-lg">📘</span>
        </div>

        <!-- اسم المادة -->
        <h1
          class="text-2xl sm:text-3xl font-extrabold tracking-tight
                 bg-clip-text text-transparent
                 bg-gradient-to-r from-slate-900 via-sky-700 to-indigo-700"
        >
          {{ courseName }}
        </h1>

        <!-- شارة كود المادة -->
        <span
          v-if="courseCode"
          class="inline-flex items-center rounded-full bg-sky-50 px-3 py-1
                 text-xs font-semibold text-sky-700 ring-1 ring-sky-200"
        >
          {{ courseCode }}
        </span>
      </div>
    </template>

    <!-- خلفية تقنية متدرّجة + ضوء خلفي -->
    <div
      class="min-h-screen px-4 sm:px-6 lg:px-10 py-6 relative overflow-hidden
             bg-gradient-to-b from-slate-50 via-sky-100/70 to-blue-200/60
             backdrop-blur-sm"
    >
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-12 left-1/3 w-80 h-80 bg-sky-200/40 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-6 right-1/4 w-96 h-96 bg-blue-300/30 rounded-full blur-3xl"></div>
      </div>

      <!-- بطاقات الإحصائيات -->
      <div class="relative grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="rounded-2xl p-5 text-slate-900 shadow-lg ring-1 ring-slate-200 bg-gradient-to-br from-sky-200 via-sky-300 to-blue-400 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
          <p class="text-xs font-medium text-slate-800">Total Sessions</p>
          <p class="mt-1 text-4xl font-extrabold leading-tight">{{ s.total }}</p>
        </div>
        <div class="rounded-2xl p-5 text-slate-900 shadow-lg ring-1 ring-emerald-200 bg-gradient-to-br from-emerald-200 via-teal-300 to-emerald-400 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
          <p class="text-xs font-medium text-slate-800">Present</p>
          <p class="mt-1 text-4xl font-extrabold leading-tight">{{ s.present }}</p>
        </div>
        <div class="rounded-2xl p-5 text-slate-900 shadow-lg ring-1 ring-rose-200 bg-gradient-to-br from-rose-200 via-pink-300 to-rose-400 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
          <p class="text-xs font-medium text-slate-800">Absent</p>
          <p class="mt-1 text-4xl font-extrabold leading-tight">{{ s.absent }}</p>
        </div>
        <div class="rounded-2xl p-5 text-slate-900 shadow-lg ring-1 ring-indigo-200 bg-gradient-to-br from-indigo-200 via-blue-300 to-cyan-400 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
          <p class="text-xs font-medium text-slate-800">Attendance</p>
          <p class="mt-1 text-4xl font-extrabold leading-tight">{{ s.rate }}%</p>
        </div>
      </div>

      <!-- تنبيه -->
      <div v-if="alert" class="relative mt-6 flex items-start gap-4 rounded-2xl border border-amber-300 bg-amber-100/70 p-4 shadow-sm ring-1 ring-amber-200">
        <div>
          <h4 class="text-sm font-semibold text-amber-900">Attendance Warning</h4>
          <p class="text-amber-800 mt-0.5 text-sm">{{ alert }}</p>
        </div>
      </div>

      <!-- تفاصيل + جدول -->
      <div class="relative mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- يسار: Course Information + Schedules -->
        <div class="flex flex-col gap-6">
          <div class="bg-white/90 rounded-2xl shadow-lg ring-1 ring-slate-200 hover:shadow-xl transition-all duration-300">
            <div class="h-1.5 w-full rounded-t-2xl bg-gradient-to-r from-sky-400 via-blue-500 to-indigo-500 opacity-90"></div>
            <div class="p-5">
              <h3 class="text-lg font-bold mb-3 text-slate-900">Course Information</h3>
              <div class="grid grid-cols-1">
                <div class="flex justify-between border-t border-slate-200 py-3 first:border-t-0">
                  <p class="text-slate-500 text-sm font-medium">Instructor</p>
                  <p class="text-slate-900 text-sm font-semibold">{{ teacherName }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white/90 rounded-2xl shadow-lg ring-1 ring-slate-200 hover:shadow-xl transition-all duration-300">
            <div class="h-1.5 w-full rounded-t-2xl bg-gradient-to-r from-sky-400 via-blue-500 to-indigo-500 opacity-90"></div>
            <div class="p-5">
              <h3 class="text-lg font-bold mb-3 text-slate-900">Lecture Schedules</h3>
              <div v-if="schedules.length" class="space-y-2">
                <div
                  v-for="sch in schedules"
                  :key="sch.id"
                  class="flex items-center justify-between rounded-lg bg-sky-50/80 px-3 py-2 text-sm text-slate-900 ring-1 ring-sky-100"
                >
                  <p class="font-medium">{{ sch.day ?? sch.day_of_week }}</p>
                  <p>{{ sch.start_time }} - {{ sch.end_time }}</p>
                </div>
              </div>
              <p v-else class="text-sm text-slate-500">No schedules added yet.</p>
            </div>
          </div>
        </div>

        <!-- يمين: My Attendance History -->
        <div class="lg:col-span-2 bg-white/90 rounded-2xl shadow-lg ring-1 ring-slate-200 hover:shadow-xl transition-all duration-300">
          <div class="h-1.5 w-full rounded-t-2xl bg-gradient-to-r from-sky-400 via-blue-500 to-indigo-500 opacity-90"></div>
          <div class="p-5">
            <h3 class="text-lg font-bold mb-3 text-slate-900">My Attendance History</h3>

            <div v-if="attendanceRecords.length" class="rounded-lg border border-slate-200 overflow-hidden shadow-sm">
              <div class="overflow-x-auto">
                <table class="w-full table-fixed text-sm">
                  <colgroup>
                    <col class="w-1/2" />
                    <col class="w-1/4" />
                    <col class="w-1/4" />
                  </colgroup>
                  <thead class="bg-sky-100">
                    <tr>
                      <th class="px-3 py-2 text-left font-medium text-slate-900">Date</th>
                      <th class="px-3 py-2 text-left font-medium text-slate-900">Check-in</th>
                      <th class="px-3 py-2 text-left font-medium text-slate-900">Status</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-200">
                    <tr v-for="rec in attendanceRecords" :key="rec.id" class="hover:bg-sky-50/50 transition-colors">
                      <td class="px-3 py-2 text-slate-700 text-xs md:text-sm">{{ fmtDate(rec.attendance_date) }}</td>
                      <td class="px-3 py-2 text-slate-700 text-xs md:text-sm">{{ fmtTime(rec.attended_at) }}</td>
                      <td class="px-3 py-2">
                        <span
                          v-if="rec.is_present"
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] md:text-xs font-semibold bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200"
                        >
                          <span class="w-2 h-2 mr-2 rounded-full bg-emerald-500"></span> Present
                        </span>
                        <span
                          v-else
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] md:text-xs font-semibold bg-rose-100 text-rose-700 ring-1 ring-rose-200"
                        >
                          <span class="w-2 h-2 mr-2 rounded-full bg-rose-500"></span> Absent
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <p v-else class="text-center text-slate-500 py-8">
              No attendance has been recorded for this course yet.
            </p>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
