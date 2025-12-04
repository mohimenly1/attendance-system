<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';
import Chart from 'chart.js/auto';

// Props من الكنترولر
const props = defineProps({
    stats: Object,
    recentCheckIns: Array,
    notifications: Array,
    chartData: Object,
});

// مرجع للرسم البياني
const chartRef = ref(null);

// تهيئة الرسم البياني عند التحميل
onMounted(() => {
    if (chartRef.value) {
        new Chart(chartRef.value, {
            type: 'line',
            data: props.chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { 
                        grid: { display: false },
                        ticks: {
                            color: '#4B5563', // لون التسميات
                        }
                    },
                    y: {
                        grid: { 
                            borderDash: [5, 5], 
                            color: 'rgba(59, 130, 246, 0.2)' // لون خطوط الشبكة أزرق فاتح
                        },
                        ticks: {
                            callback: value => value + '%',
                            color: '#4B5563', // لون التسميات
                        }
                    }
                },
                elements: {
                    line: {
                        tension: 0.4, // لجعل الخط منحنيًا
                        borderColor: '#2563eb', // لون الخط الأساسي أزرق داكن
                        borderWidth: 3,
                    },
                    point: {
                        radius: 5,
                        backgroundColor: '#3b82f6', // لون النقاط أزرق فاتح
                        hoverRadius: 7,
                    }
                }
            }
        });
    }
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="p-6 dashboard-container min-h-screen">
            <h1 class="text-2xl font-bold text-blue-900 mb-6 flex items-center">
                <i class="fas fa-chart-line text-blue-500 text-2xl mr-3 icon-gradient"></i>
                Dashboard
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="stat-card">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Students</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ stats.totalStudents }}</p>
                    </div>
                    <i class="fas fa-users text-blue-500 text-4xl card-icon"></i>
                </div>
                
                <div class="stat-card">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Teachers</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ stats.totalTeachers }}</p>
                    </div>
                    <i class="fas fa-chalkboard-teacher text-blue-500 text-4xl card-icon"></i>
                </div>
                
                <div class="stat-card">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Courses</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ stats.totalCourses }}</p>
                    </div>
                    <i class="fas fa-book-open text-blue-500 text-4xl card-icon"></i>
                </div>
                
                <div class="stat-card">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Daily Attendance</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">
                            {{ chartData.datasets[0].data[chartData.datasets[0].data.length - 1] }}%
                        </p>
                    </div>
                    <i class="fas fa-check-circle text-blue-500 text-4xl card-icon"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg mb-8 chart-card">
                <h2 class="text-xl font-semibold text-blue-900 mb-4">Weekly Attendance Trend</h2>
                <div class="flex items-baseline mb-4">
                    <p class="text-4xl font-bold text-gray-900">
                        {{ chartData.datasets[0].data[chartData.datasets[0].data.length - 1] }}%
                    </p>
                    <span class="text-sm text-green-600 font-medium ml-2 bg-green-100 px-2 py-0.5 rounded-full border border-green-300">
                        <i class="fas fa-arrow-up text-xs mr-1"></i>
                        vs last week
                    </span>
                </div>
                <div style="height: 300px;">
                    <canvas ref="chartRef"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-lg list-card">
                    <h2 class="text-xl font-semibold text-blue-900 mb-4">Recent Check-Ins</h2>
                    <ul>
                        <li v-for="(item, index) in recentCheckIns" :key="index" class="flex justify-between items-center py-3 border-b last:border-b-0 border-gray-100 hover:bg-blue-50 transition-colors rounded-md px-2 -mx-2">
                            <span class="text-gray-800 font-medium flex items-center">
                                <i class="fas fa-user-check text-blue-400 mr-2 text-lg"></i>
                                {{ item.name }}
                            </span>
                            <span class="text-gray-500 text-sm bg-blue-100 px-2 rounded-full">{{ item.time }}</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg list-card">
                    <h2 class="text-xl font-semibold text-blue-900 mb-4">Notifications</h2>
                    <ul>
                        <li v-for="(notification, index) in notifications" :key="index"
                            :class="['flex items-start p-3 rounded-lg mb-2 notification-item',
                                    notification.type === 'alert' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-blue-50 text-blue-700 border border-blue-200']">
                            <i :class="['fas', notification.icon, 'mr-3 mt-1', notification.type === 'alert' ? 'text-red-500' : 'text-blue-500']"></i>
                            <p class="text-sm font-medium">{{ notification.message }}</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* -------------------------- */
/* Global Container Styling (الخلفية الزرقاء الهادئة) */
/* -------------------------- */
.dashboard-container {
    /* تطبيق الخلفية الزرقاء الهادئة */
    background: linear-gradient(135deg, #f5faff 0%, #e6f3ff 100%);
}

/* -------------------------- */
/* Header Icon Gradient */
/* -------------------------- */
.icon-gradient {
    /* تطبيق تدرج لوني على الأيقونة النصية */
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.text-blue-900 {
    color: #1e3a8a; /* لضمان لون أزرق داكن متناسق */
}

/* -------------------------- */
/* Stat Card Styling */
/* -------------------------- */
.stat-card {
    background: #ffffff;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05), 0 0 0 1px rgba(59, 130, 246, 0.1); /* ظل خفيف مع حدود زرقاء */
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3), 0 0 0 1px rgba(59, 130, 246, 0.15);
}

.card-icon {
    /* تلوين الأيقونات بتدرج لوني لتأثير أكثر حيوية */
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    filter: drop-shadow(0 2px 2px rgba(0,0,0,0.1));
}

/* -------------------------- */
/* Main Cards (Chart & Lists) */
/* -------------------------- */
.chart-card, .list-card {
    border-radius: 1rem; /* rounded-xl */
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
}

/* -------------------------- */
/* Notification Styling */
/* -------------------------- */
.notification-item {
    transition: all 0.2s ease;
}
.notification-item:hover {
    filter: brightness(0.98);
}
</style>