<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gradient tracking-wide mb-2">
        Attendance Records for {{ course.name }}
      </h2>
    </template>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-4 sm:py-8">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
          <div
            v-for="record in attendanceRecords"
            :key="record.id"
            class="bg-gray-800 p-4 rounded-lg shadow-md"
          >
            <h4 class="text-white font-semibold">{{ record.student.name }}</h4>
            <div class="text-sm text-slate-400">Status: {{ record.status ? 'Present' : 'Absent' }}</div>
            <div class="text-sm text-slate-400">Date: {{ record.attended_at }}</div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
const props = defineProps({
  course: Object, // بيانات المادة
  attendanceRecords: Array, // بيانات الحضور
});
</script>
