<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    courses: Array,
});
</script>

<template>
    <Head title="Course Management" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Course Management</h2>
                <Link :href="route('admin.courses.create')" class="px-4 py-2 bg-gray-800 text-white rounded-md">Add Course</Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left">Name</th>
                                <th class="px-6 py-3 text-left">Code</th>
                                <th class="px-6 py-3 text-left">Teacher</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="course in courses" :key="course.id">
                                <td class="px-6 py-4">{{ course.name }}</td>
                                <td class="px-6 py-4">{{ course.code }}</td>
                                <td class="px-6 py-4">{{ course.teacher.name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>