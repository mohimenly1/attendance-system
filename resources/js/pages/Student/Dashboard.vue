<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3'; // Import Link

defineProps({
    courses: Array,
});
</script>

<template>
    <Head title="Student Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Courses</h2>
        </template>

        <div class="space-y-6">
            <Link
                v-for="course in courses"
                :key="course.id"
                :href="route('student.courses.show', course.id)"
                class="block p-4 sm:p-8 bg-white shadow sm:rounded-lg hover:bg-gray-50 transition"
            >
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">{{ course.name }}</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Taught by: {{ course.teacher.name }}
                        </p>
                    </header>
                </section>
            </Link>

            <div v-if="!courses.length" class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <p class="text-center text-gray-500">You are not enrolled in any courses yet.</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>