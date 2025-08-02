<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    teachers: Array,
});

const form = useForm({
    name: '',
    code: '',
    description: '',
    teacher_id: null,
});

const submit = () => {
    form.post(route('admin.courses.store'));
};
</script>

<template>
    <Head title="Add New Course" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add New Course</h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit">
                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700">Name</label>
                            <input v-model="form.name" id="name" type="text" class="mt-1 block w-full" />
                            <div v-if="form.errors.name" class="text-sm text-red-600">{{ form.errors.name }}</div>
                        </div>

                        <div class="mt-4">
                            <label for="code" class="block font-medium text-sm text-gray-700">Code</label>
                            <input v-model="form.code" id="code" type="text" class="mt-1 block w-full" />
                            <div v-if="form.errors.code" class="text-sm text-red-600">{{ form.errors.code }}</div>
                        </div>

                        <div class="mt-4">
                            <label for="description" class="block font-medium text-sm text-gray-700">Description</label>
                            <textarea v-model="form.description" id="description" class="mt-1 block w-full"></textarea>
                        </div>

                        <div class="mt-4">
                            <label for="teacher_id" class="block font-medium text-sm text-gray-700">Teacher</label>
                            <select v-model="form.teacher_id" id="teacher_id" class="mt-1 block w-full">
                                <option :value="null" disabled>Select a teacher</option>
                                <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">{{ teacher.name }}</option>
                            </select>
                            <div v-if="form.errors.teacher_id" class="text-sm text-red-600">{{ form.errors.teacher_id }}</div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-gray-800 text-white rounded-md">Save Course</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>