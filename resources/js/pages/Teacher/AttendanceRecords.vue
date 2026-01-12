<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

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

const filterDate = ref('')

const normalizeDate = (val) => (val ? String(val).slice(0, 10) : '')

const filteredRecords = computed(() => {
  if (!filterDate.value) return props.attendanceRecords
  const target = filterDate.value
  return props.attendanceRecords.filter((r) => {
    const d = normalizeDate(r.date ?? r.attendance_date ?? r.created_at)
    return d === target
  })
})

const resetFilter = () => (filterDate.value = '')

const statusLabel = (isPresent) => (isPresent ? 'Present' : 'Absent')
</script>

<template>
  <Head :title="`Attendance - ${course?.name ?? ''}`" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <h2 class="page-title text-gradient text-center sm:text-left">
          سجل الحضور — {{ course?.name }}
        </h2>

        <Link
          :href="route('teacher.dashboard')"
          class="btn-back w-full sm:w-auto text-center"
        >
          ← Back
        </Link>
      </div>
    </template>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-6">
      <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="panel">
          <!-- 🎛️ الرأس -->
          <div class="panel-head flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
            <div></div>
            <div class="flex flex-col sm:flex-row flex-wrap items-center justify-end gap-3">
              <div class="flex items-center gap-2 w-full sm:w-auto justify-between sm:justify-start">
                <span class="text-xs text-slate-300">Date</span>
                <input v-model="filterDate" type="date" class="date-input w-full sm:w-auto" />
              </div>

              <span class="chip-count w-full sm:w-auto text-center sm:text-left">
                عدد السجلات: {{ filteredRecords.length }}
              </span>

              <button type="button" class="btn-reset w-full sm:w-auto" @click="resetFilter">
                Reset
              </button>

              <a
                :href="route('teacher.courses.attendanceRecords.pdf', course.id)"
                target="_blank"
                class="btn-download w-full sm:w-auto text-center"
              >
                PDF
              </a>
            </div>
          </div>

          <!-- 📊 جدول (للكمبيوتر فقط) -->
          <div class="overflow-x-auto hidden sm:block mt-4">
            <table class="min-w-max w-full divide-y divide-slate-700 text-xs sm:text-sm">
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
                  v-for="record in filteredRecords"
                  :key="record.id"
                  class="hover:bg-gray-800/70 transition-colors"
                >
                  <td class="td-cell">
                    {{ (record.date ?? record.attendance_date ?? record.created_at ?? '—')
                      ?.toString()
                      .slice(0, 10) }}
                  </td>

                  <td class="td-cell">
                    {{ record.student?.name ?? record.student_name ?? '—' }}
                  </td>

                  <td class="td-cell text-center">
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

                <tr v-if="!filteredRecords.length">
                  <td colspan="4" class="px-4 py-6 text-center text-slate-500 text-sm">
                    لا توجد سجلات حضور لهذه المادة حتى الآن.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- 📱 بطاقات (للهواتف فقط) -->
          <div class="grid grid-cols-1 sm:hidden gap-3 mt-4">
            <div
              v-for="record in filteredRecords"
              :key="record.id"
              class="bg-gray-900/80 border border-slate-700 rounded-lg p-4 flex flex-col gap-2"
            >
              <div class="flex justify-between items-center">
                <span class="text-xs text-slate-400">📅 التاريخ:</span>
                <span class="text-slate-200 font-medium">
                  {{ (record.date ?? record.attendance_date ?? record.created_at)?.toString().slice(0, 10) }}
                </span>
              </div>

              <div class="flex justify-between items-center">
                <span class="text-xs text-slate-400">👨‍🎓 الطالب:</span>
                <span class="text-slate-100 font-semibold">
                  {{ record.student?.name ?? record.student_name ?? '—' }}
                </span>
              </div>

              <div class="flex justify-between items-center">
                <span class="text-xs text-slate-400">🟢 الحالة:</span>
                <span
                  class="status-pill"
                  :class="record.is_present ? 'status-present' : 'status-absent'"
                >
                  {{ statusLabel(record.is_present) }}
                </span>
              </div>

              <div class="flex justify-between items-center">
                <span class="text-xs text-slate-400">🕓 اليوم / الوقت:</span>
                <template v-if="record.schedule">
                  <span class="text-slate-300 text-xs">
                    {{ record.schedule.day_of_week }}<br />
                    {{ record.schedule.start_time }} - {{ record.schedule.end_time }}
                  </span>
                </template>
                <span v-else>—</span>
              </div>
            </div>

            <div v-if="!filteredRecords.length" class="text-center text-slate-500 text-sm py-4">
              لا توجد سجلات حضور بعد.
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.page-title {
  font-size: 1.3rem;
  font-weight: 600;
}

.text-gradient {
  background-image: linear-gradient(to right, #4f46e5, #3b82f6);
  -webkit-background-clip: text;
  color: transparent;
}

.panel {
  @apply rounded-xl border border-slate-700 bg-gray-900/90 backdrop-blur-md shadow-lg overflow-hidden;
}

.panel-head {
  @apply px-4 sm:px-6 py-4 border-b border-slate-700 bg-gradient-to-r from-gray-900/80 to-gray-900/40;
}

.chip-count {
  @apply inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-medium
         bg-slate-800 text-slate-200 border border-slate-600;
}

.table-head {
  @apply bg-gray-900;
}

.th-cell {
  @apply px-3 sm:px-4 py-2 text-left text-xs font-medium text-slate-400 uppercase tracking-wider;
}

.td-cell {
  @apply px-3 sm:px-4 py-2 text-slate-200;
}

.status-pill {
  @apply inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium border;
}

.status-present {
  @apply bg-emerald-500/15 text-emerald-300 border-emerald-500/40;
}

.status-absent {
  @apply bg-rose-500/15 text-rose-300 border-rose-500/40;
}

.btn-back {
  @apply inline-flex items-center justify-center gap-1 rounded-md px-3 py-1.5 text-xs sm:text-sm font-medium
         bg-slate-900 text-slate-200 border border-slate-600
         hover:bg-slate-800 hover:border-slate-400 transition-colors;
}

.btn-download {
  @apply inline-flex items-center justify-center rounded-md px-3 py-1.5 text-xs font-medium
         bg-sky-600 text-white border border-sky-500
         hover:bg-sky-500 hover:border-sky-400 transition-colors;
}

.date-input {
  @apply h-9 rounded-md border border-slate-600 bg-slate-900/70 text-slate-100 text-xs px-3
         focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500;
}

.btn-reset {
  @apply inline-flex items-center justify-center rounded-md px-3 py-1.5 text-xs font-medium
         bg-slate-800 text-slate-200 border border-slate-600
         hover:bg-slate-700 hover:border-slate-400 transition-colors;
}
</style>
