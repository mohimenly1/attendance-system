<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';
import Chart from 'chart.js/auto';

// Props from the controller
const props = defineProps({
    stats: Object,
    recentCheckIns: Array,
    notifications: Array,
    chartData: Object,
});

// Chart reference
const chartRef = ref(null);

// Initialize the chart on mounted
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
                            color: '#4B5563',
                        }
                    },
                    y: {
                        grid: {
                            borderDash: [5, 5],
                            color: 'rgba(59, 130, 246, 0.2)',
                        },
                        ticks: {
                            callback: value => value + '%',
                            color: '#4B5563',
                        }
                    }
                },
                elements: {
                    line: {
                        tension: 0.4,
                        borderColor: '#2563eb',
                        borderWidth: 3,
                    },
                    point: {
                        radius: 5,
                        backgroundColor: '#3b82f6',
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


 <div class="p-6 dashboard-container min-h-screen">        <!-- Title -->
        <h2 class="text-3xl font-semibold text-gradient tracking-wide mb-2">
Dashboard        </h2>


            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="stat-card">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Students</p>
                        <p class="text-3xl font-semibold text-white mt-1">{{ stats.totalStudents }}</p>
                    </div>
                    <i class="fas fa-users text-gradient text-4xl card-icon"></i>
                </div>

                <div class="stat-card">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Teachers</p>
                        <p class="text-3xl font-semibold text-white mt-1">{{ stats.totalTeachers }}</p>
                    </div>
                    <i class="fas fa-chalkboard-teacher text-gradient text-4xl card-icon"></i>
                </div>

                <div class="stat-card">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Courses</p>
                        <p class="text-3xl font-semibold text-white mt-1">{{ stats.totalCourses }}</p>
                    </div>
                    <i class="fas fa-book-open text-gradient text-4xl card-icon"></i>
                </div>

                <div class="stat-card">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Daily Attendance</p>
                        <p class="text-3xl font-semibold text-white mt-1">
                            {{ chartData.datasets[0].data[chartData.datasets[0].data.length - 1] }}%
                        </p>
                    </div>
                    <i class="fas fa-check-circle text-gradient text-4xl card-icon"></i>
                </div>
            </div>

            <div class="chart-card">
                <h2 class="text-xl font-semibold text-white mb-4">Weekly Attendance Trend</h2>
                <div class="flex items-baseline mb-4">
                    <p class="text-4xl font-bold text-white">
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
                <div class="list-card">
                    <h2 class="text-xl font-semibold text-white mb-4">Recent Check-Ins</h2>
                    <ul>
                        <li v-for="(item, index) in recentCheckIns" :key="index" class="recent-check-in-item">
                            <span class="text-white font-medium flex items-center">
                                <i class="fas fa-user-check text-blue-400 mr-2 text-lg"></i>
                                {{ item.name }}
                            </span>
                            <span class="text-gray-500 text-sm bg-blue-100 px-2 rounded-full">{{ item.time }}</span>
                        </li>
                    </ul>
                </div>

                <div class="list-card">
                    <h2 class="text-xl font-semibold text-white mb-4">Notifications</h2>
                    <ul>
                        <li v-for="(notification, index) in notifications" :key="index" class="notification-item">
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

/* Background gradient container (subtle blue) */
.text-gradient {
    background-image: linear-gradient(to right, #4F46E5, #3B82F6); /* Gradient from Indigo to Blue */
    -webkit-background-clip: text; /* This property makes the gradient apply to text */
    color: transparent; /* Ensures the gradient is visible in the text */
}
/* Container for table */
.table-container {
  width: 100%; /* Takes up full width */
  max-width: 1200px; /* Optional: Limits the max width of the table */
  margin: 0 auto; /* Centers the table */
}

/* Table itself */
table {
  width: 40%; /* Takes up full width of the container */
  max-width: 100%; /* Ensures table width is within its container */
}

.text-gradient:hover {
    background-image: linear-gradient(to right, #2563EB, #4338CA); /* Darker gradient on hover */
}

.text-gradient {
    font-size: 2.5rem; /* Increased font size for title */
    font-weight: 700; /* Bold text */
    text-transform: uppercase; /* Uppercase letters for a bold impact */
    letter-spacing: 2px; /* Adjusted letter spacing */
    text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.2); /* Shadow effect to add depth */
}

/* Icon gradient look */
.icon-gradient {
  color: #4f46e5; /* Indigo-600 */
}



/* Stat Card Styling */
.stat-card {
    background: linear-gradient(135deg, #0b1220 0%, #0f1b2d 100%);
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05), 0 0 0 1px rgba(59, 130, 246, 0.1), 0 0 10px rgba(59, 130, 246, 0.3);  /* Added glow effect */
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3), 0 0 0 1px rgba(59, 130, 246, 0.15), 0 0 20px rgba(59, 130, 246, 0.5);  /* Stronger glow on hover */
}

/* Chart Card Styling */
.chart-card {
    background: linear-gradient(135deg, #0b1220 0%, #0f1b2d 100%);
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05), 0 0 10px rgba(59, 130, 246, 0.3);  /* Added glow effect */
}

/* List Card Styling */
.list-card {
    background: linear-gradient(135deg, #0b1220 0%, #0f1b2d 100%);
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05), 0 0 10px rgba(59, 130, 246, 0.3);  /* Added glow effect */
}

/* Notification Styling */
.notification-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    background-color: #1f2937;  /* Dark background to make the text stand out */
    margin-bottom: 1rem;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
}

.notification-item:hover {
    background-color: #3b82f6;  /* Glow effect on hover */
    color: white;
}

/* For highlighted notifications */
.notification-item .highlighted {
    background-color: #2563eb;  /* Dark blue background */
    color: white;
    padding: 0.5rem;
    border-radius: 8px;
    font-weight: bold;
}

/* Adjust color and visibility for highlighted text */
.notification-item .highlighted:hover {
    background-color: #1d4ed8; /* Slightly darker on hover */
}

/* For Notifications that are critical or alerting */
.notification-item.alert {
    background-color: #f9fafb; /* Lighter background for alerts */
    color: #dc2626; /* Dark red for alert */
}

/* On hover, make the alert more noticeable */
.notification-item.alert:hover {
    background-color: #f3f4f6;
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
}
</style>
