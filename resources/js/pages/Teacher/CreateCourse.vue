<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    code: '',
    description: '',
});

const submit = () => {
    form.post(route('teacher.courses.store'));
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
                            <label for="name">Name</label>
                            <input v-model="form.name" id="name" type="text" class="mt-1 block w-full" />
                            <div v-if="form.errors.name" class="text-sm text-red-600">{{ form.errors.name }}</div>
                        </div>
                        <div class="mt-4">
                            <label for="code">Code</label>
                            <input v-model="form.code" id="code" type="text" class="mt-1 block w-full" />
                            <div v-if="form.errors.code" class="text-sm text-red-600">{{ form.errors.code }}</div>
                        </div>
                        <div class="mt-4">
                            <label for="description">Description</label>
                            <textarea v-model="form.description" id="description" class="mt-1 block w-full"></textarea>
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