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
const s = computed(() => ({
  total: props.stats?.total ?? 0,
  present: props.stats?.present ?? 0,
  absent: props.stats?.absent ?? 0,
  rate: props.stats?.rate ?? 0,
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
    <!-- ===== Header ===== -->
    <template #header>
      <div class="flex items-center gap-3">
        <div
          class="h-9 w-9 rounded-xl
                 bg-gradient-to-br from-blue-500 to-indigo-600
                 shadow-md flex items-center justify-center"
        >
          <span class="text-white text-lg">📘</span>
        </div>

        <h1
          class="text-xl sm:text-2xl lg:text-3xl font-extrabold tracking-tight
                 bg-clip-text text-transparent
                 bg-gradient-to-r from-blue-300 via-blue-400 to-indigo-400"
        >
          {{ courseName }}
        </h1>

        <span
          v-if="courseCode"
          class="inline-flex items-center rounded-full
                 bg-blue-500/10 px-3 py-1 text-xs font-semibold
                 text-blue-300 ring-1 ring-blue-400/30"
        >
          {{ courseCode }}
        </span>
      </div>
    </template>

    <!-- ===== Page Body ===== -->
    <div
      class="min-h-screen px-4 sm:px-6 lg:px-10 py-6 relative overflow-hidden
             bg-radial-gradient"
    >
      <!-- Glow -->
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-20 left-1/3 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-[28rem] h-[28rem] bg-indigo-600/20 rounded-full blur-3xl"></div>
      </div>

      <!-- ===== Stats ===== -->
      <div class="relative grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="stat-box">
          <p class="stat-label">Total Sessions</p>
          <p class="stat-value">{{ s.total }}</p>
        </div>
        <div class="stat-box emerald">
          <p class="stat-label">Present</p>
          <p class="stat-value">{{ s.present }}</p>
        </div>
        <div class="stat-box rose">
          <p class="stat-label">Absent</p>
          <p class="stat-value">{{ s.absent }}</p>
        </div>
        <div class="stat-box cyan">
          <p class="stat-label">Attendance</p>
          <p class="stat-value">{{ s.rate }}%</p>
        </div>
      </div>

      <!-- ===== Alert ===== -->
      <div
        v-if="alert"
        class="relative mt-6 rounded-2xl border border-amber-400/40
               bg-amber-400/10 p-4 shadow-lg"
      >
        <h4 class="text-sm font-semibold text-amber-300">Attendance Warning</h4>
        <p class="text-amber-200 mt-1 text-sm">{{ alert }}</p>
      </div>

      <!-- ===== Details + Table ===== -->
      <div class="relative mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left -->
        <div class="flex flex-col gap-6">
          <div class="panel-card">
            <h3 class="panel-title">Course Information</h3>
            <div class="flex justify-between border-t border-blue-400/10 pt-3">
              <p class="panel-label">Instructor</p>
              <p class="panel-value">{{ teacherName }}</p>
            </div>
          </div>

          <div class="panel-card">
            <h3 class="panel-title">Lecture Schedules</h3>
            <div v-if="schedules.length" class="space-y-2">
              <div
                v-for="sch in schedules"
                :key="sch.id"
                class="flex justify-between rounded-lg
                       bg-blue-500/10 px-3 py-2
                       text-sm text-blue-100 ring-1 ring-blue-400/20"
              >
                <p class="font-medium">{{ sch.day ?? sch.day_of_week }}</p>
                <p>{{ sch.start_time }} - {{ sch.end_time }}</p>
              </div>
            </div>
            <p v-else class="text-sm text-blue-300/60">No schedules added yet.</p>
          </div>
        </div>

        <!-- Right -->
        <div class="lg:col-span-2 panel-card">
          <h3 class="panel-title">My Attendance History</h3>

          <div v-if="attendanceRecords.length" class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead class="bg-blue-500/10">
                <tr>
                  <th class="px-3 py-2 text-left text-blue-200">Date</th>
                  <th class="px-3 py-2 text-left text-blue-200">Check-in</th>
                  <th class="px-3 py-2 text-left text-blue-200">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-blue-400/10">
                <tr
                  v-for="rec in attendanceRecords"
                  :key="rec.id"
                  class="hover:bg-blue-500/5 transition"
                >
                  <td class="px-3 py-2 text-blue-100">{{ fmtDate(rec.attendance_date) }}</td>
                  <td class="px-3 py-2 text-blue-100">{{ fmtTime(rec.attended_at) }}</td>
                  <td class="px-3 py-2">
                    <span
                      v-if="rec.is_present"
                      class="badge-present"
                    >Present</span>
                    <span
                      v-else
                      class="badge-absent"
                    >Absent</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <p v-else class="text-center text-blue-300/60 py-8">
            No attendance has been recorded for this course yet.
          </p>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.bg-radial-gradient{
  background: radial-gradient(circle at top, #0f172a, #020617);
}

/* ===== Stats ===== */
.stat-box{
  background: linear-gradient(180deg, #020617, #020617);
  border-radius: 18px;
  padding: 1.25rem;
  box-shadow: inset 0 0 30px rgba(59,130,246,.25), 0 15px 40px rgba(0,0,0,.7);
}
.stat-label{
  font-size: .75rem;
  color: #93c5fd;
}
.stat-value{
  font-size: clamp(1.75rem, 4vw, 2.5rem);
  font-weight: 800;
  color: #e5f0ff;
}
.stat-box.emerald{ box-shadow: inset 0 0 30px rgba(16,185,129,.25), 0 15px 40px rgba(0,0,0,.7); }
.stat-box.rose{ box-shadow: inset 0 0 30px rgba(244,63,94,.25), 0 15px 40px rgba(0,0,0,.7); }
.stat-box.cyan{ box-shadow: inset 0 0 30px rgba(34,211,238,.25), 0 15px 40px rgba(0,0,0,.7); }

/* ===== Panels ===== */
.panel-card{
  background: linear-gradient(180deg, #020617, #020617);
  border-radius: 18px;
  padding: 1.25rem;
  box-shadow: inset 0 0 30px rgba(59,130,246,.25), 0 15px 40px rgba(0,0,0,.7);
}
.panel-title{
  color: #bfdbfe;
  font-weight: 700;
  margin-bottom: .75rem;
}
.panel-label{ color:#93c5fd; font-size:.85rem; }
.panel-value{ color:#e5f0ff; font-size:.85rem; font-weight:600; }

/* ===== Badges ===== */
.badge-present{
  background: rgba(16,185,129,.15);
  color:#6ee7b7;
  padding:.25rem .6rem;
  border-radius:999px;
  font-size:.75rem;
}
.badge-absent{
  background: rgba(244,63,94,.15);
  color:#fda4af;
  padding:.25rem .6rem;
  border-radius:999px;
  font-size:.75rem;
}
</style>
