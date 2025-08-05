<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    photos: [], // Add photos array to the form
});

// To store image previews
const photoPreviews = ref([]);

const handlePhotoUpload = (event) => {
    form.photos = Array.from(event.target.files); // Update form photos
    photoPreviews.value = []; // Clear previous previews

    // Generate new previews
    if (form.photos.length) {
        form.photos.forEach(file => {
            const reader = new FileReader();
            reader.onload = (e) => {
                photoPreviews.value.push(e.target.result);
            };
            reader.readAsDataURL(file);
        });
    }
};

const submit = () => {
    form.post(route('admin.students.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Add New Student" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">إضافة طالب جديد</h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700">اسم الطالب</label>
                            <input v-model="form.name" id="name" type="text" class="mt-1 block w-full" required />
                            <div v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name }}</div>
                        </div>
                        <div>
                            <label for="email" class="block font-medium text-sm text-gray-700">البريد الإلكتروني</label>
                            <input v-model="form.email" id="email" type="email" class="mt-1 block w-full" required />
                            <div v-if="form.errors.email" class="text-sm text-red-600 mt-1">{{ form.errors.email }}</div>
                        </div>
                        <div>
                            <label for="password" class="block font-medium text-sm text-gray-700">كلمة المرور</label>
                            <input v-model="form.password" id="password" type="password" class="mt-1 block w-full" required />
                            <div v-if="form.errors.password" class="text-sm text-red-600 mt-1">{{ form.errors.password }}</div>
                        </div>
                        <div>
                            <label for="password_confirmation" class="block font-medium text-sm text-gray-700">تأكيد كلمة المرور</label>
                            <input v-model="form.password_confirmation" id="password_confirmation" type="password" class="mt-1 block w-full" required />
                        </div>

                        <div>
                            <label for="photos" class="block font-medium text-sm text-gray-700">صور الطالب (يمكن اختيار أكثر من صورة)</label>
                            <input @change="handlePhotoUpload" id="photos" type="file" class="mt-1 block w-full" multiple accept="image/*" />
                            <div v-if="form.errors.photos" class="text-sm text-red-600 mt-1">{{ form.errors.photos }}</div>
                        </div>

                        <div v-if="photoPreviews.length > 0" class="mt-4 grid grid-cols-3 gap-4">
                            <div v-for="(preview, index) in photoPreviews" :key="index">
                                <img :src="preview" class="h-24 w-24 object-cover rounded-md" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" :disabled="form.processing" class="px-6 py-2 bg-gray-800 text-white font-semibold rounded-md hover:bg-gray-700">
                                إنشاء حساب الطالب
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>