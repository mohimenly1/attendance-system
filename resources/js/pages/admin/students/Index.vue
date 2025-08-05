<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    students: Array,
});

// Helper function to get the full URL for a student's photo
const getPhotoUrl = (photoPath) => {
    // Assumes files are in storage/app/public and storage has been linked
    return `/storage/${photoPath}`;
};
</script>

<template>
    <Head title="Student Management" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">إدارة الطلاب</h2>
                <!-- The "Add Student" button can link to the teacher's creation route for now -->
                <Link :href="route('admin.students.create')" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm font-semibold">
                    إضافة طالب جديد
                </Link>
            </div>
        </template>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الصورة</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">البريد الإلكتروني</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">المواد المسجلة</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="student in students" :key="student.id">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img 
                                    v-if="student.photos.length > 0" 
                                    :src="getPhotoUrl(student.photos[0].photo_path)" 
                                    alt="Student Photo" 
                                    class="h-10 w-10 rounded-full object-cover"
                                >
                                <div v-else class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-xs text-gray-500">
                                    No Photo
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ student.name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ student.email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ student.courses.length }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">تعديل</a>
                                <a href="#" class="text-red-600 hover:text-red-900">حذف</a>
                            </td>
                        </tr>
                        <tr v-if="students.length === 0">
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                لا يوجد طلاب مسجلون في النظام حاليًا.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>