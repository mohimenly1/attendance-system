<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

// استقبال البيانات من الـ Controller
const { course, unenrolledStudents } = defineProps({
    course: {
        type: Object,
        required: true,
    },
    unenrolledStudents: {
        type: Array,
        default: () => [],
    },
});

// نموذج لتسجيل طالب في المادة
const enrollForm = useForm({
    student_id: null,
});

// إرسال طلب تسجيل طالب في المادة
const submitEnrollment = () => {
    enrollForm.post(route('teacher.courses.enroll', course.id), {
        preserveScroll: true,
        onSuccess: () => enrollForm.reset(),
    });
};
</script>

<template>
    <Head :title="course.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gradient leading-tight">
                    {{ course.name }} ({{ course.code }})
                </h2>

                <!-- زر رجوع للداشبورد -->
                <Link
                    :href="route('teacher.dashboard')"
                    class="text-sm text-sky-300 hover:text-sky-200 hover:underline transition"
                >
Back                </Link>
            </div>
        </template>

        <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-8">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                <!-- 🧑‍🎓 الطلاب المسجلون + تسجيل طالب جديد -->
                <section class="panel space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">
                            الطلاب المسجلون في المادة
                        </h3>
                        <span class="text-xs px-2 py-1 rounded-full bg-sky-500/10 text-sky-300 border border-sky-500/30">
                            {{ course.students ? course.students.length : 0 }} طالب
                        </span>
                    </div>

                    <div class="mt-2">
                        <ul
                            v-if="course.students && course.students.length > 0"
                            class="divide-y divide-slate-700/70"
                        >
                            <li
                                v-for="student in course.students"
                                :key="student.id"
                                class="py-3 flex justify-between items-center text-sm"
                            >
                                <span class="text-slate-100">
                                    {{ student.name }}
                                </span>
                            </li>
                        </ul>

                        <p v-else class="text-center text-slate-500 py-4 text-sm">
                            لم يتم تسجيل أي طالب في هذه المادة بعد.
                        </p>
                    </div>

                    <!-- فورم تسجيل طالب جديد في المادة -->
                    <form @submit.prevent="submitEnrollment" class="mt-4 pt-4 border-t border-slate-700/70 space-y-3">
                        <h4 class="font-medium text-slate-100">
                            تسجيل طالب جديد في المادة
                        </h4>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <select
                                v-model="enrollForm.student_id"
                                class="flex-grow rounded-lg border border-slate-600 bg-slate-900/70 text-slate-100 text-sm px-3 py-2
                                       focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                            >
                                <option :value="null" disabled>اختر طالباً لتسجيله</option>
                                <option
                                    v-for="student in unenrolledStudents"
                                    :key="student.id"
                                    :value="student.id"
                                >
                                    {{ student.name }}
                                </option>
                            </select>

                            <button
                                type="submit"
                                :disabled="enrollForm.processing"
                                class="px-4 py-2 rounded-lg text-sm font-semibold
                                       bg-gradient-to-r from-sky-500 to-indigo-500 text-white
                                       hover:from-sky-400 hover:to-indigo-400
                                       disabled:opacity-60 disabled:cursor-not-allowed
                                       transition shadow-[0_10px_30px_-15px_rgba(56,189,248,0.8)]"
                            >
                                تسجيل
                            </button>
                        </div>

                        <div
                            v-if="enrollForm.errors.student_id"
                            class="text-xs text-red-400 mt-1"
                        >
                            {{ enrollForm.errors.student_id }}
                        </div>
                    </form>
                </section>

                <!-- ⏱️ زر بدء جلسة الحضور -->
                <section class="panel space-y-3">




                    <Link
                        :href="route('teacher.attendance.start', course.id)"
                        as="button"
                         class="w-full mt-2 py-3 px-4 rounded-lg text-sm font-semibold text-white
           bg-gradient-to-r from-sky-500 to-indigo-500
           hover:from-sky-400 hover:to-indigo-400
           shadow-[0_14px_40px_-18px_rgba(56,189,248,0.6)]
           transition duration-300"
                    >
             بدء جلسة تسجيل الحضور
                    </Link>
                </section>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.text-gradient {
  background-image: linear-gradient(to right, #4F46E5, #3B82F6);
  -webkit-background-clip: text;
  color: transparent;
}

.panel {
  @apply rounded-2xl border border-slate-700 bg-gray-800/90 backdrop-blur-md
         shadow-[0_18px_45px_-24px_rgba(15,118,230,0.65)] px-5 py-5;
}
</style>
