<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3'; // Import Link

defineProps({
    courses: Array,
});
</script>

<template>
    <Head title="Teacher Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Teacher Dashboard</h2>
        </template>

        <div class="flex justify-between items-center">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Teacher Dashboard</h2>
    <Link :href="route('teacher.courses.create')" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">Add New Course</Link>
</div>
        <div class="space-y-6">
            <Link 
                v-for="course in courses" 
                :key="course.id" 
                :href="route('teacher.courses.show', course.id)" 
                class="block p-4 sm:p-8 bg-white shadow sm:rounded-lg hover:bg-gray-50 transition"
            >
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">{{ course.name }} ({{ course.code }})</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ course.description }}
                        </p>
                    </header>
                </section>
            </Link>

             <div v-if="!courses.length" class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <p class="text-center text-gray-500">You are not assigned to any courses yet.</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>