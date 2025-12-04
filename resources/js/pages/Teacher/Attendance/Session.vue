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

// --- إرسال الكود عبر البريد ---
const manualCode = ref('');

const sendEmailWithCode = (code) => {
    if (!code) {
        alert('Please enter a valid code');
        return;
    }

    axios.post(route('teacher.attendance.sendCode'), { code })
        .then(() => {
            alert(`Attendance code sent to the email.`);
        })
        .catch(error => {
            console.error('Error sending code: ', error);
        });
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
                    <!-- تحسين خلفية الفيديو -->
                    <div class="md:col-span-2 p-4 bg-gradient-to-r from-gray-800 via-gray-900 to-black rounded-lg shadow-lg overflow-hidden">
                        <video ref="video" autoplay playsinline class="w-full h-auto rounded-md shadow-lg"></video>
                        <canvas ref="canvas" class="hidden"></canvas>
                    </div>

                    <div class="md:col-span-1 p-4 bg-gradient-to-r from-[#1E293B] via-[#0F172A] to-[#1E293B] rounded-lg shadow-lg">
                        <h3 class="text-lg font-medium text-white mb-4">Attendance Status</h3>
                        <ul class="space-y-3">
                            <li v-for="att in attendanceList" :key="att.id" class="flex items-center justify-between">
                                <span class="text-gray-300">{{ att.student.name }}</span>
                                <span v-if="att.is_present" class="px-3 py-1 text-xs font-semibold rounded-full bg-green-300 text-green-800">
                                    Present
                                </span>
                                <span v-else class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-500 text-gray-100">
                                    Absent
                                </span>
                            </li>
                        </ul>

                        <!-- Manual Attendance Section -->
                        <div class="mt-6 space-y-4">
                            <label for="manual-code" class="block text-sm font-medium text-gray-300">Enter Attendance Code</label>
                            <input v-model="manualCode" type="text" id="manual-code" placeholder="Enter code" class="mt-1 p-2 rounded-lg border-gray-300 w-full text-sm text-gray-700 bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            <button @click="sendEmailWithCode(manualCode)" class="mt-2 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 w-full">
                                Send Code to Email
                            </button>
                        </div>
                    </div>
                </div>
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

button {
  transition: all 0.3s ease;
}

button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.bg-green-200 {
  background-color: #e0f4e1;
}

.bg-gray-500 {
  background-color: #6b7280;
}

.bg-gray-200 {
  background-color: #f2f2f2;
}

.bg-gradient-to-r {
  background-image: linear-gradient(to right, #1e293b, #0f172a);
}

.bg-blue-600 {
  background-color: #2563eb;
}

.bg-blue-700 {
  background-color: #1d4ed8;
}
</style>
