<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    course: Object,
    schedule: Object,
    todaysAttendance: Array,
});

const video = ref(null);
const canvas = ref(null);
let stream = null;
let captureInterval = null;

const attendanceList = ref(props.todaysAttendance.map(att => ({ ...att })));

/* ===== Camera ===== */
const startCamera = async () => {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
        if (video.value) video.value.srcObject = stream;
    } catch (err) {
        console.error(err);
        alert('Could not access camera');
    }
};

const stopCamera = () => {
    if (stream) stream.getTracks().forEach(t => t.stop());
    if (captureInterval) clearInterval(captureInterval);
};

const captureAndSendFrame = () => {
    if (!video.value || !canvas.value || video.value.videoWidth === 0) return;

    const ctx = canvas.value.getContext('2d');
    canvas.value.width = video.value.videoWidth;
    canvas.value.height = video.value.videoHeight;
    ctx.drawImage(video.value, 0, 0);

    canvas.value.toBlob(blob => {
        if (!blob) return;

        const formData = new FormData();
        formData.append('image', blob, 'frame.jpg');
        formData.append('schedule_id', props.schedule.id);

        axios.post(route('teacher.attendance.mark'), formData)
            .then(res => {
                if (res.data.status === 'success') {
                    const id = Number(res.data.student_id);
                    const student = attendanceList.value.find(a => Number(a.student_id) === id);
                    if (student && !student.is_present) student.is_present = true;
                }
            });
    }, 'image/jpeg');
};

const endSession = () => {
    if (confirm('End attendance session?')) {
        stopCamera();
        router.post(route('teacher.attendance.end', { course: props.course.id }), {
            schedule_id: props.schedule.id,
        });
    }
};

onMounted(() => {
    startCamera();
    captureInterval = setInterval(captureAndSendFrame, 3000);
});

onUnmounted(stopCamera);
</script>

<template>
    <Head title="Attendance Session" />

    <AuthenticatedLayout>
        <!-- ===== Header ===== -->
        <template #header>
            <div class="flex flex-col gap-1">
                <!-- اسم المادة -->
              <h1
  class="text-xl sm:text-2xl lg:text-3xl font-extrabold tracking-tight
         bg-clip-text text-transparent
         bg-gradient-to-r from-indigo-400 via-blue-400 to-cyan-400"
>
  {{ course.name }}
</h1>


                <!-- موعد الجلسة -->
                <p class="text-sm sm:text-base text-slate-400">
                    {{ schedule.day }}
                    ({{ schedule.start_time }} - {{ schedule.end_time }})
                </p>
            </div>
        </template>

        <!-- ===== Page Body ===== -->
        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <!-- End Session -->
                <div class="mb-6 flex justify-end">
                    <button
                        @click="endSession"
                        class="px-5 py-2.5 rounded-xl font-semibold text-white
                               bg-gradient-to-r from-red-600 to-rose-600
                               shadow-lg hover:from-red-700 hover:to-rose-700"
                    >
                        End Session & Notify Absentees
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- Camera -->
                    <div
                        class="md:col-span-2 p-4 rounded-2xl
                               bg-gradient-to-br from-slate-900 via-slate-950 to-black
                               shadow-[0_20px_50px_rgba(0,0,0,.8)]"
                    >
                        <video
                            ref="video"
                            autoplay
                            playsinline
                            class="w-full rounded-xl shadow-lg"
                        ></video>
                        <canvas ref="canvas" class="hidden"></canvas>
                    </div>

                    <!-- Attendance List -->
                    <div
                        class="p-5 rounded-2xl
                               bg-gradient-to-br from-slate-800 via-slate-900 to-slate-800
                               shadow-[0_20px_50px_rgba(0,0,0,.7)]"
                    >
                        <h3 class="text-lg font-bold text-slate-200 mb-4">
                            Attendance Status
                        </h3>

                        <ul class="space-y-3">
                            <li
                                v-for="att in attendanceList"
                                :key="att.id"
                                class="flex items-center justify-between"
                            >
                                <span class="text-slate-300">
                                    {{ att.student.name }}
                                </span>

                                <span
                                    v-if="att.is_present"
                                    class="px-3 py-1 text-xs font-bold rounded-full
                                           bg-emerald-400/20 text-emerald-300"
                                >
                                    Present
                                </span>

                                <span
                                    v-else
                                    class="px-3 py-1 text-xs font-bold rounded-full
                                           bg-slate-500/30 text-slate-200"
                                >
                                    Absent
                                </span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* no global overrides needed */
</style>
