<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    course: Object,
    attendanceRecords: Array,
});
</script>

<template>
    <Head :title="course.name" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ course.name }} - {{ course.code }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="md:col-span-1 space-y-6">
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900">Course Information</h3>
                        <p class="mt-1 text-sm text-gray-600">Taught by: {{ course.teacher.name }}</p>
                    </div>
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Lecture Schedules</h3>
                        <ul v-if="course.schedules.length > 0" class="space-y-2">
                            <li v-for="schedule in course.schedules" :key="schedule.id" class="text-gray-700">
                                <strong>{{ schedule.day_of_week }}:</strong> {{ schedule.start_time }} - {{ schedule.end_time }}
                            </li>
                        </ul>
                        <p v-else class="text-sm text-gray-500">No schedules added yet.</p>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">My Attendance History</h3>
                        <div v-if="attendanceRecords.length > 0">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="record in attendanceRecords" :key="record.id">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ record.attendance_date }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span v-if="record.is_present" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Present
                                            </span>
                                            <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Absent
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p v-else class="text-center text-gray-500 py-4">No attendance has been recorded for this course yet.</p>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>