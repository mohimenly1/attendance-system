<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

// استقبال البيانات من الـ Controller
const props = defineProps({
    course: Object,
    unenrolledStudents: Array, // قائمة الطلاب غير المسجلين
});

// نموذج لإضافة محاضرة جديدة
const scheduleForm = useForm({
    day_of_week: '',
    start_time: '',
    end_time: '',
});

// نموذج لإضافة طالب جديد للمادة
const enrollForm = useForm({
    student_id: null,
});

// دالة لإرسال طلب إضافة محاضرة
const submitSchedule = () => {
    scheduleForm.post(route('teacher.schedules.store', props.course.id), {
        preserveScroll: true, // للحفاظ على مكان الصفحة بعد التحديث
        onSuccess: () => scheduleForm.reset(),
    });
};

// دالة لإرسال طلب إضافة طالب
const submitEnrollment = () => {
    enrollForm.post(route('teacher.courses.enroll', props.course.id), {
        preserveScroll: true,
        onSuccess: () => enrollForm.reset(),
    });
};
</script>

<template>
    <Head :title="course.name" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ course.name }} - ({{ course.code }})
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="md:col-span-1 space-y-6">
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">مواعيد المحاضرات</h3>
                        <ul v-if="course.schedules.length > 0" class="space-y-2">
                            <li v-for="schedule in course.schedules" :key="schedule.id" class="text-gray-700">
                                <strong>{{ schedule.day_of_week }}:</strong> {{ schedule.start_time }} - {{ schedule.end_time }}
                            </li>
                        </ul>
                        <p v-else class="text-sm text-gray-500">لم يتم إضافة أي مواعيد بعد.</p>
                    </div>

                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                         <h3 class="text-lg font-medium text-gray-900 mb-4">إضافة موعد جديد</h3>
                         <form @submit.prevent="submitSchedule" class="space-y-4">
                            <div>
                                <label for="day_of_week" class="block text-sm font-medium text-gray-700">اليوم</label>
                                <select v-model="scheduleForm.day_of_week" id="day_of_week" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option disabled value="">اختر اليوم</option>
                                    <option>Sunday</option>
                                    <option>Monday</option>
                                    <option>Tuesday</option>
                                    <option>Wednesday</option>
                                    <option>Thursday</option>
                                </select>
                                <div v-if="scheduleForm.errors.day_of_week" class="text-sm text-red-600 mt-1">{{ scheduleForm.errors.day_of_week }}</div>
                            </div>
                            <div>
                                <label for="start_time" class="block text-sm font-medium text-gray-700">وقت البدء</label>
                                <input type="time" v-model="scheduleForm.start_time" id="start_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                                <div v-if="scheduleForm.errors.start_time" class="text-sm text-red-600 mt-1">{{ scheduleForm.errors.start_time }}</div>
                            </div>
                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700">وقت الانتهاء</label>
                                <input type="time" v-model="scheduleForm.end_time" id="end_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                                 <div v-if="scheduleForm.errors.end_time" class="text-sm text-red-600 mt-1">{{ scheduleForm.errors.end_time }}</div>
                            </div>
                            <button type="submit" :disabled="scheduleForm.processing" class="w-full py-2 px-4 bg-gray-800 text-white font-bold rounded-md hover:bg-gray-700">إضافة موعد</button>
                         </form>
                    </div>
                </div>

                <div class="md:col-span-2 space-y-6">
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">الطلاب المسجلون ({{ course.students.length }})</h3>
                        <ul v-if="course.students.length > 0" class="divide-y divide-gray-200">
                            <li v-for="student in course.students" :key="student.id" class="py-3 flex justify-between items-center">
                                <span class="text-gray-700">{{ student.name }}</span>
                            </li>
                        </ul>
                        <p v-else class="text-center text-gray-500 py-4">لم يتم تسجيل أي طالب في هذه المادة بعد.</p>

                        <form @submit.prevent="submitEnrollment" class="mt-6 border-t pt-4">
                             <h4 class="font-medium text-gray-800 mb-2">تسجيل طالب جديد في المادة</h4>
                             <div class="flex items-start space-x-2">
                                <select v-model="enrollForm.student_id" class="flex-grow rounded-md border-gray-300 shadow-sm">
                                    <option :value="null" disabled>اختر طالباً لتسجيله</option>
                                    <option v-for="student in unenrolledStudents" :key="student.id" :value="student.id">
                                        {{ student.name }}
                                    </option>
                                </select>
                                <button type="submit" :disabled="enrollForm.processing" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">تسجيل</button>
                             </div>
                             <div v-if="enrollForm.errors.student_id" class="text-sm text-red-600 mt-1">{{ enrollForm.errors.student_id }}</div>
                        </form>
                    </div>
                    
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                         <h3 class="text-lg font-medium text-gray-900 mb-4">جلسة الحضور</h3>
                         <button class="w-full py-3 px-4 bg-green-600 text-white font-bold rounded-md hover:bg-green-700">
                             بدء جلسة تسجيل الحضور
                         </button>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>