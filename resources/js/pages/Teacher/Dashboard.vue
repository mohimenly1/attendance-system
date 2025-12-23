<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';

const props = defineProps({
  teacherName: { type: String, default: '' },
  courses: { type: Array, default: () => [] },
  summaries: { type: Array, default: () => [] },
  stats: { type: Object, default: () => ({ attendanceRate: 0 }) },
  today: { type: String, default: '' },
});

const page = usePage();

// Daily stats
const attendanceRate = ref(props.stats?.attendanceRate ?? 0);
const absenceRate = ref(props.stats ? 100 - props.stats.attendanceRate : 100);

async function loadTodayStats() {
  if (!route().has('teacher.stats.today')) return;
  try {
    const res = await fetch(route('teacher.stats.today'));
    const data = await res.json();
    attendanceRate.value = data.attendanceRate ?? 0;
    absenceRate.value = data.absenceRate ?? (100 - attendanceRate.value);
  } catch {}
}
onMounted(loadTodayStats);

// Course details link (للاستعمال مع ملخص اليوم)
const detailsHref = (courseId) => route('teacher.courses.show', courseId);

// Active course selection
const activeCourseId = ref(props.courses?.[0]?.id ?? null);
const selectCourse = (id) => { activeCourseId.value = id };

// Start session for the active course
const startActiveSession = (e, courseId) => {
  e?.preventDefault();
  if (!courseId) return;
  const url = route('teacher.courses.add_student', courseId);
  router.get(url, {}, { preserveScroll: true });
};

// Search courses
const q = ref('');
const filteredCourses = computed(() => {
  const term = q.value.trim().toLowerCase();
  if (!term) return props.courses;
  return props.courses.filter(c => {
    const inName = (c.name || '').toLowerCase().includes(term);
    const inSched = (c.schedules || []).some(s =>
      `${s.day || s.day_of_week} ${s.start || s.start_time} ${s.end || s.end_time}`
        .toLowerCase()
        .includes(term)
    );
    return inName || inSched;
  });
});

// Current day
const todayName = computed(() => {
  if (props.today) return props.today;
  return new Date().toLocaleDateString('en-US', { weekday: 'long' });
});

// Get today's schedule
const getTodaySchedule = (course) => {
  if (!Array.isArray(course.schedules)) return null;

  return (
    course.schedules.find((s) => {
      const day = s.day_of_week || s.day;
      return day === todayName.value;
    }) || null
  );
};

// Time formatting
const formatTime = (time) => {
  if (!time) return '';
  return time.toString().slice(0, 5);
};

// View attendance record (سجل حضور المادّة)
const viewAttendanceRecord = (courseId) => {
  const url = route('teacher.courses.attendanceRecords', courseId);
  router.get(url, {}, { preserveScroll: true });
};
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gradient tracking-wide mb-2">
        Dashboard
      </h2>
    </template>

      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">

        <div class="flex flex-col lg:flex-row-reverse gap-8">
          <!-- RIGHT PANEL (بوكس المواد) -->
          <div class="lg:w-1/3">
            <aside class="panel h-fit">
              <div class="panel-head flex flex-col gap-3">
                <div class="flex items-center justify-between gap-3">
                  <h3 class="panel-title text-white">My Courses &amp; Schedule</h3>

                  <button
                    class="btn-primary-sm"
                    :disabled="!activeCourseId"
                    @click="startActiveSession($event, activeCourseId)"
                    :class="{ 'opacity-60 cursor-not-allowed': !activeCourseId }"
                  >
                    START
                  </button>
                </div>

                <div class="relative">
                  <input
                    v-model="q"
                    type="text"
                    placeholder="Search courses…"
                    class="w-full rounded-lg border border-slate-200/70 bg-white/80 backdrop-blur px-3 py-2 text-sm text-slate-700"
                  />
                </div>
              </div>

              <div class="p-3 grid gap-3">
                <div
                  v-for="crs in filteredCourses"
                  :key="crs.id"
                  class="course-card-sm cursor-pointer"
                  :class="{
                    'tab-active': activeCourseId === crs.id,
                    'tab-inactive': activeCourseId !== crs.id,
                    'bg-sky-600': activeCourseId !== crs.id
                  }"
                  @click="selectCourse(crs.id)"
                >
                  <div class="flex items-start gap-2.5">
                    <div class="icon-chip-sm"></div>

                    <div class="min-w-0 flex-1">
                      <div class="font-semibold text-white truncate text-sm">
                        {{ crs.name }}
                      </div>

                      <!-- TODAY'S SCHEDULE ONLY -->
                      <div class="mt-1 text-[11px] leading-5">
                        <template v-if="getTodaySchedule(crs)">
                          <div class="flex items-center gap-1">
                            <div class="bg-slate-700 rounded-lg text-xs text-white px-2 py-1">
                              {{ todayName }}:
                              {{ formatTime(getTodaySchedule(crs).start_time || getTodaySchedule(crs).start) }}
                              -
                              {{ formatTime(getTodaySchedule(crs).end_time || getTodaySchedule(crs).end) }}
                            </div>
                          </div>
                        </template>

                        <span v-else class="text-slate-500 italic">
                          لا توجد محاضرة اليوم
                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="mt-2 h-1.5 w-full rounded-full bg-transparent tab-indicator"></div>
                </div>

                <div v-if="!filteredCourses.length" class="text-center text-slate-400 py-4 text-sm">
                  No matching courses.
                </div>
              </div>
            </aside>
          </div>

          <!-- LEFT SIDE (الجزء الذي يحتوي على الرسومات الأخرى) -->
          <div class="lg:w-2/3 space-y-6">
            <!-- Welcome card -->
            <section class="rounded-lg overflow-hidden shadow-lg ring-1 ring-sky-100/80">
              <div class="bg-gradient-to-r from-[#2a3a59] via-[#3d4f70] to-[#1a2b42] px-5 sm:px-8 py-5 sm:py-6">
                <div class="flex items-center gap-4">
                  <div>
                    <p class="text-xs sm:text-sm text-slate-400 tracking-wide">WELCOME BACK</p>
                    <h3 class="text-lg sm:text-2xl font-extrabold text-gradient leading-snug">
                      {{ props.teacherName || 'Professor' }}
                    </h3>
                    <p class="mt-1 text-xs sm:text-sm text-slate-300">
                      Wishing you a wonderful day full of giving
                    </p>
                  </div>
                </div>
              </div>
            </section>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="progress-bar-container">
                <div class="text-slate-300 text-sm">Daily Attendance</div>
                <div class="mt-2 text-4xl font-extrabold text-white">{{ attendanceRate }}%</div>
                <div class="progress-bar">
                  <div
                    class="progress-fill bg-gradient-to-r from-indigo-600 to-sky-400"
                    :style="{ width: `${attendanceRate}%` }"
                  ></div>
                </div>
              </div>

              <div class="progress-bar-container">
                <div class="text-slate-300 text-sm">Absence Percentage</div>
                <div class="mt-2 text-4xl font-extrabold text-white">{{ absenceRate }}%</div>
                <div class="progress-bar">
                  <div
                    class="progress-fill bg-gradient-to-r from-red-600 to-orange-400"
                    :style="{ width: `${absenceRate}%` }"
                  ></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- أسفل الصفحة: المواد + زر سجل الحضور -->
        <div class="lg:w-full mt-8 panel">
          <div class="panel-head">
            <h3 class="panel-title">Courses &amp; Attendance Records</h3>
          </div>

          <div class="divide-y divide-slate-700/60">
            <div
              v-for="course in props.courses"
              :key="course.id"
              class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 px-4 py-3"
            >
              <div>
                <div class="text-white font-semibold">
                  {{ course.name }}
                </div>
                <div class="text-xs text-slate-400 mt-1" v-if="course.schedules?.length">
                  {{ course.schedules[0].day || course.schedules[0].day_of_week }}
                  -
                  {{ formatTime(course.schedules[0].start || course.schedules[0].start_time) }}
                  إلى
                  {{ formatTime(course.schedules[0].end || course.schedules[0].end_time) }}
                </div>
              </div>

              <button
                class="btn-primary-sm"
                @click="viewAttendanceRecord(course.id)"
              >
                عرض سجل الحضور
              </button>
            </div>

            <div
              v-if="!props.courses.length"
              class="px-4 py-6 text-center text-slate-400 text-sm"
            >
              لا توجد مواد متاحة حالياً.
            </div>
          </div>
        </div>

      </div>
  </AuthenticatedLayout>
</template>

<style scoped>

.text-gradient {
    font-size: 1.5rem; /* Increased font size for title */
    font-weight: 700; /* Bold text */
    text-transform: uppercase; /* Uppercase letters for a bold impact */
    letter-spacing: 2px; /* Adjusted letter spacing */
    text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.2); /* Shadow effect to add depth */
}

.text-gradient {
  background-image: linear-gradient(to right, #441cf3, #2168db);
  -webkit-background-clip: text;
  color: transparent;
}

.panel {
  @apply rounded-lg border border-slate-700 bg-gray-800/90 backdrop-blur-md;
  padding: 12px;
}

.panel-head {
  @apply px-4 py-2 border-b border-slate-600 bg-gradient-to-r from-gray-900/70 to-gray-900/30;
}

.panel-title {
  @apply font-semibold text-slate-100 text-sm;
}

/* ✅ بوكس اليوم */
.today-badge {
  @apply text-xs font-semibold px-3 py-1 rounded-full
         bg-sky-500/15 text-sky-300 border border-sky-500/40;
}

.progress-bar-container {
  @apply rounded-lg p-4 shadow-md bg-gray-800;
}

.progress-bar {
  @apply mt-3 w-full h-2 rounded-full bg-gray-700 overflow-hidden;
}

.progress-fill {
  @apply h-full rounded-full transition-all duration-300;
}

.course-card-sm {
  @apply rounded-lg border border-slate-700 bg-gray-800/90 backdrop-blur-md p-3
         hover:-translate-y-0.5 hover:border-sky-600 transition-all duration-300;
}

.course-card-sm.tab-active {
  border-color: rgba(79, 96, 135, 0.9);
  box-shadow:
    0 10px 30px -15px rgba(76, 18, 157, 0.4),
    0 0 0 1px rgba(56, 189, 248, 0.4) inset;
  background: linear-gradient(180deg, #020617 0%, #0f172a 100%);
}

.course-card-sm .tab-indicator {
  transition: width 0.25s ease;
}

.course-card-sm.tab-active .tab-indicator {
  background: linear-gradient(90deg, #160132, #0ea5e9);
}

.icon-chip-sm {
  @apply flex h-8 w-8 items-center justify-center rounded-lg
         bg-gradient-to-br from-sky-900 to-slate-800 ring-1 ring-sky-500/40 text-base;
}

.btn-light {
  @apply inline-flex items-center gap-2 rounded-md px-3 py-1.5 text-xs sm:text-sm font-medium
         text-sky-300 bg-slate-800 border border-sky-500/60
         hover:bg-slate-700 hover:border-sky-400
         focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-sky-500;
}

.btn-primary-sm {
  @apply inline-flex items-center justify-center rounded-md px-3 py-1.5
         text-xs font-semibold text-white
         bg-gradient-to-r from-blue-600 to-sky-500
         hover:from-blue-700 hover:to-sky-600
         shadow-[0_6px_18px_-8px_rgba(2,132,199,0.40)]
         transition-colors;
}

.bg-slate-700 {
  background-color: #374151;
}
</style>
