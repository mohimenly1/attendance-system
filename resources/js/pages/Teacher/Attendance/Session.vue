<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3'; // استيراد router
import { ref, onMounted, onUnmounted, reactive } from 'vue';
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

// Make attendance reactive so the UI updates (ref for arrays)
const attendanceList = ref(props.todaysAttendance.map(att => ({ ...att })));

const startCamera = async () => {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
        if (video.value) {
            video.value.srcObject = stream;
        }
    } catch (err) {
        console.error("Error accessing camera: ", err);
        alert("Could not access the camera. Please check permissions.");
    }
};

const stopCamera = () => {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }
    if (captureInterval) {
        clearInterval(captureInterval);
    }
};

const captureAndSendFrame = () => {
    if (!video.value || !canvas.value || video.value.videoWidth === 0) {
        return;
    }

    const context = canvas.value.getContext('2d');
    canvas.value.width = video.value.videoWidth;
    canvas.value.height = video.value.videoHeight;
    context.drawImage(video.value, 0, 0, canvas.value.width, canvas.value.height);

    canvas.value.toBlob((blob) => {
        if (!blob) {
            console.error("Failed to capture frame from canvas.");
            return;
        }

        const formData = new FormData();
        formData.append('image', blob, 'frame.jpg');
        formData.append('schedule_id', props.schedule.id);

        axios.post(route('teacher.attendance.mark'), formData)
            .then(response => {
                if (response.data.status === 'success') {
                    // توحيد النوع قبل المطابقة
                    const recognizedId = Number(response.data.student_id);

                    // attendanceList أصبحت ref لذلك نستخدم .value
                    const studentToUpdate = attendanceList.value.find(
                        att => Number(att.student_id) === recognizedId
                    );

                    if (studentToUpdate && !studentToUpdate.is_present) {
                        // تحويل الحالة من Absent إلى Present
                        studentToUpdate.is_present = true;
                        // لو عندك أعلام أخرى لزر Mark فعّلها هنا
                        // studentToUpdate.canMark = true;
                    }
                }
            })
            .catch(error => {
                // console.log("Not recognized or error:", error?.response?.data ?? error);
            });
    }, 'image/jpeg');
};

// --- دالة جديدة لإنهاء الجلسة ---
const endSession = () => {
    if (confirm('Are you sure you want to end the session? This will send notifications to absent students.')) {
        stopCamera();
        router.post(route('teacher.attendance.end', { course: props.course.id }), {
            schedule_id: props.schedule.id,
        });
    }
};

onMounted(() => {
    startCamera();
    // Capture a frame every 3 seconds
    captureInterval = setInterval(captureAndSendFrame, 3000);
});

onUnmounted(() => {
    stopCamera();
});
</script>

<template>
    <Head title="Attendance Session" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Live Attendance: {{ course.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div class="mb-4 flex justify-end">
                    <form @submit.prevent="endSession">
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-opacity-75">
                            End Session & Notify Absentees
                        </button>
                    </form>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 p-4 bg-black rounded-lg shadow">
                        <video ref="video" autoplay playsinline class="w-full h-auto rounded-md"></video>
                        <canvas ref="canvas" class="hidden"></canvas>
                    </div>

                    <div class="md:col-span-1 p-4 bg-white rounded-lg shadow">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Attendance Status</h3>
                        <ul class="space-y-3">
                            <li v-for="att in attendanceList" :key="att.id" class="flex items-center justify-between">
                                <span class="text-gray-800">{{ att.student.name }}</span>
                                <span v-if="att.is_present" class="px-3 py-1 text-xs font-semibold rounded-full bg-green-200 text-green-800">
                                    Present
                                </span>
                                <span v-else class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-800">
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
