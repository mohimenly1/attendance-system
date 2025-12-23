<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
  course: {
    type: Object,
    required: true,
  },
  attendanceRecords: {
    type: Array,
    default: () => [],
  },
})

// تعديل دالة statusLabel لعرض الحالة باللغتين العربية والإنجليزية
const statusLabel = (isPresent) => {
  if (isPresent) {
    return ' Present';
  } else {
    return ' Absent';
  }
}
</script>

<template>
  <Head :title="`Attendance - ${course?.name ?? ''}`" />

  <AuthenticatedLayout>
    <!-- الهيدر -->
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="page-title text-gradient">
          سجل الحضور — {{ course?.name }}
        </h2>

        <Link
          :href="route('teacher.dashboard')"
          class="btn-back"
        >
          ← Back
        </Link>
      </div>
    </template>

    <!-- المحتوى -->
    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-6">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="panel">
          <!-- رأس الكارد -->
          <div class="panel-head flex items-center justify-between">
            <div>
            </div>

            <div class="flex items-center gap-3">

              <span class="chip-count">
                عدد السجلات: {{ attendanceRecords.length }}
              </span>

              <!-- ✅ زر تحميل PDF (مضاف فقط بدون أي تغيير آخر) -->
              <a
                :href="route('teacher.courses.attendanceRecords.pdf', course.id)"
                target="_blank"
                class="btn-download"
              >
                 PDF
              </a>

            </div>
          </div>

          <!-- جدول السجلات -->
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-700 text-sm">
              <thead class="table-head">
                <tr>
                  <th class="th-cell">التاريخ</th>
                  <th class="th-cell">الطالب</th>
                  <th class="th-cell">الحالة</th>
                  <th class="th-cell">اليوم / الوقت</th>
                </tr>
              </thead>

              <tbody class="bg-gray-900/60 divide-y divide-slate-800">
                <tr
                  v-for="record in attendanceRecords"
                  :key="record.id"
                  class="hover:bg-gray-800/70 transition-colors"
                >
                  <td class="td-cell">
                    {{ record.date ?? record.attendance_date ?? '—' }}
                  </td>

                  <td class="td-cell">
                    {{ record.student?.name ?? record.student_name ?? '—' }}
                  </td>

                  <td class="td-cell">
                    <span
                      class="status-pill"
                      :class="record.is_present ? 'status-present' : 'status-absent'"
                    >
                      {{ statusLabel(record.is_present) }}
                    </span>
                  </td>

                  <td class="td-cell text-xs">
                    <template v-if="record.schedule">
                      <div class="flex flex-col">
                        <span class="text-slate-200 font-medium">
                          {{ record.schedule.day_of_week }}
                        </span>
                        <span class="text-slate-400">
                          {{ record.schedule.start_time }} - {{ record.schedule.end_time }}
                        </span>
                      </div>
                    </template>
                    <span v-else>—</span>
                  </td>
                </tr>

                <tr v-if="!attendanceRecords.length">
                  <td colspan="4" class="px-4 py-6 text-center text-slate-500 text-sm">
                    لا توجد سجلات حضور لهذه المادة حتى الآن.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>

      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.page-title {
  font-size: 1.4rem;
  font-weight: 600;
}

.text-gradient {
  background-image: linear-gradient(to right, #4f46e5, #3b82f6);
  -webkit-background-clip: text;
  color: transparent;
  letter-spacing: 0.06em;
}

.panel {
  @apply rounded-xl border border-slate-700 bg-gray-900/90 backdrop-blur-md shadow-lg overflow-hidden;
}

.panel-head {
  @apply px-4 sm:px-6 py-4 border-b border-slate-700 bg-gradient-to-r from-gray-900/80 to-gray-900/40;
}

.panel-title {
  @apply text-sm sm:text-base font-semibold text-slate-100;
}

.chip-count {
  @apply inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
         bg-slate-800 text-slate-200 border border-slate-600;
}

.table-head {
  @apply bg-gray-900;
}

.th-cell {
  @apply px-4 py-2 text-left text-xs font-medium text-slate-400 uppercase tracking-wider;
}

.td-cell {
  @apply px-4 py-2 text-slate-200;
}

.status-pill {
  @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border;
}

.status-present {
  @apply bg-emerald-500/15 text-emerald-300 border-emerald-500/40;
}

.status-absent {
  @apply bg-rose-500/15 text-rose-300 border-rose-500/40;
}

.btn-back {
  @apply inline-flex items-center gap-1 rounded-md px-3 py-1.5 text-xs sm:text-sm font-medium
         bg-slate-900 text-slate-200 border border-slate-600
         hover:bg-slate-800 hover:border-slate-400 transition-colors;
}

/* ✅ تنسيق زر تحميل PDF */
.btn-download {
  @apply inline-flex items-center rounded-md px-3 py-1.5 text-xs font-medium
         bg-sky-600 text-white border border-sky-500
         hover:bg-sky-500 hover:border-sky-400
         transition-colors;
}
</style>
