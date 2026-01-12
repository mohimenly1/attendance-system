<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

// استقبال البيانات من الـ Controller
const props = defineProps({
  course: { type: Object, required: true },
  schedules: { type: Array, default: () => [] },
  courseStats: {
    type: Object,
    default: () => ({
      attendanceRate: 0,
      absenceRate: 0,
      totalRecords: 0,
      presentCount: 0,
      absentCount: 0,
      totalSessions: 0,
    }),
  },
});

// ✅ fallback: لو schedules ما جتش كـ prop خذيها من course.schedules
const schedulesList = computed(() => {
  if (Array.isArray(props.schedules) && props.schedules.length) return props.schedules;
  return props.course?.schedules ?? [];
});

// Helpers
const formatTime = (time) => {
  if (!time) return '';
  return time.toString().slice(0, 5);
};
</script>

<template>
  <Head :title="props.course.name" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <h2 class="font-semibold text-xl text-gradient leading-tight text-center sm:text-left">
          {{ props.course.name }} ({{ props.course.code }})
        </h2>

        <Link
          :href="route('teacher.dashboard')"
          class="text-sm text-sky-300 hover:text-sky-200 hover:underline transition text-center sm:text-left"
        >
          ← Back
        </Link>
      </div>
    </template>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-8">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- ✅ تقسيم الصفحة -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

          <!-- ✅ LEFT -->
          <div class="lg:col-span-2 space-y-6">

            <!-- 🧑‍🎓 قائمة الطلاب فقط -->
            <section class="panel space-y-4">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">الطلاب المسجلون في المادة</h3>
                <span class="text-xs px-2 py-1 rounded-full bg-sky-500/10 text-sky-300 border border-sky-500/30">
                  {{ props.course.students ? props.course.students.length : 0 }} طالب
                </span>
              </div>

              <div class="mt-2">
                <ul
                  v-if="props.course.students && props.course.students.length > 0"
                  class="divide-y divide-slate-700/70"
                >
                  <li
                    v-for="student in props.course.students"
                    :key="student.id"
                    class="py-3 flex justify-between items-center text-sm"
                  >
                    <span class="text-slate-100">{{ student.name }}</span>
                  </li>
                </ul>

                <p v-else class="text-center text-slate-500 py-4 text-sm">
                  لم يتم تسجيل أي طالب في هذه المادة بعد.
                </p>
              </div>
            </section>

            <!-- ⏱️ زر بدء جلسة الحضور -->
            <section class="panel space-y-3">
              <Link
                :href="route('teacher.attendance.start', props.course.id)"
                as="button"
                class="w-full mt-2 py-3 px-4 rounded-lg text-sm font-semibold text-white
                       bg-gradient-to-r from-sky-500 to-indigo-500
                       hover:from-sky-400 hover:to-indigo-400
                       shadow-[0_14px_40px_-18px_rgba(56,189,248,0.6)]
                       transition duration-300 text-center"
              >
                بدء جلسة تسجيل الحضور
              </Link>
            </section>

          </div>

          <!-- ✅ RIGHT -->
          <aside class="lg:col-span-1 space-y-6">

            <!-- 📅 Lecture Schedule -->
            <section class="panel">
              <div class="flex items-center justify-between mb-3">
                <h3 class="text-white font-semibold">Lecture Schedule</h3>
                <span class="text-xs px-2 py-1 rounded-full bg-sky-500/10 text-sky-200 border border-sky-500/30">
                  {{ schedulesList.length }} Slots
                </span>
              </div>

              <div v-if="schedulesList.length" class="space-y-2">
                <div
                  v-for="s in schedulesList"
                  :key="s.id"
                  class="rounded-lg border border-slate-700 bg-slate-900/40 px-3 py-2"
                >
                  <div class="flex items-center justify-between">
                    <span class="text-slate-100 text-sm font-semibold">
                      {{ s.day_of_week || s.day }}
                    </span>

                    <span class="text-xs text-slate-300">
                      {{ formatTime(s.start_time || s.start) }} - {{ formatTime(s.end_time || s.end) }}
                    </span>
                  </div>

                  <div v-if="s.room" class="text-xs text-slate-400 mt-1">
                    Room: {{ s.room }}
                  </div>
                </div>
              </div>
            </section>

            <!-- 📊 Course Attendance Stats -->
            <section class="panel">
              <h3 class="text-white font-semibold mb-3">Course Attendance Stats</h3>

              <div class="grid grid-cols-2 gap-3">
                <div class="rounded-lg border border-slate-700 bg-slate-900/40 p-3">
                  <div class="text-xs text-slate-400">Attendance</div>
                  <div class="mt-1 text-2xl font-extrabold text-white">
                    {{ props.courseStats.attendanceRate }}%
                  </div>
                </div>

                <div class="rounded-lg border border-slate-700 bg-slate-900/40 p-3">
                  <div class="text-xs text-slate-400">Absence</div>
                  <div class="mt-1 text-2xl font-extrabold text-white">
                    {{ props.courseStats.absenceRate }}%
                  </div>
                </div>
              </div>

              <div class="mt-3 grid grid-cols-2 gap-3">
                <div class="rounded-lg border border-slate-700 bg-slate-900/40 p-3">
                  <div class="text-xs text-slate-400">Sessions</div>
                  <div class="mt-1 text-lg font-bold text-slate-100">
                    {{ props.courseStats.totalSessions }}
                  </div>
                </div>

                <div class="rounded-lg border border-slate-700 bg-slate-900/40 p-3">
                  <div class="text-xs text-slate-400">Records</div>
                  <div class="mt-1 text-lg font-bold text-slate-100">
                    {{ props.courseStats.totalRecords }}
                  </div>
                </div>
              </div>

              <div class="mt-3 text-xs text-slate-400">
                Present:
                <span class="text-slate-200 font-semibold">{{ props.courseStats.presentCount }}</span> •
                Absent:
                <span class="text-slate-200 font-semibold">{{ props.courseStats.absentCount }}</span>
              </div>
            </section>
          </aside>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.text-gradient {
  background-image: linear-gradient(to right, #4F46E5, #3B82F6);
  -webkit-background-clip: text;
  color: transparent;
}

.panel {
  @apply rounded-2xl border border-slate-700 bg-gray-800/90 backdrop-blur-md
         shadow-[0_18px_45px_-24px_rgba(15,118,230,0.65)] px-5 py-5;
}
</style>
