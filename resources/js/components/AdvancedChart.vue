<script setup>
import { Line } from 'vue-chartjs';
import {
  Chart,
  Title,
  Tooltip,
  Legend,
  LineElement,
  CategoryScale,
  LinearScale,
  PointElement,
  Filler,
} from 'chart.js';

// تسجيل العناصر في Chart.js
Chart.register(
  Title,
  Tooltip,
  Legend,
  LineElement,
  CategoryScale,
  LinearScale,
  PointElement,
  Filler
);

// استقبال البيانات من Laravel عبر props
const props = defineProps({
  labels: { type: Array, required: true },
  data: { type: Array, required: true },
});

// الحصول على اللون الرئيسي من CSS
const primaryColor = getComputedStyle(document.documentElement)
  .getPropertyValue('--color-primary')
  .trim();

// بيانات الرسم البياني
const chartData = {
  labels: props.labels,
  datasets: [
    {
      label: 'أداء النظام',
      data: props.data,
      fill: true,
      backgroundColor: 'rgba(59, 130, 246, 0.2)', // أزرق شفاف
      borderColor: primaryColor, // اللون من CSS
      pointBackgroundColor: '#1d4ed8',
      tension: 0.4,
      borderWidth: 3,
      hoverBorderWidth: 4,
    },
  ],
};

// إعدادات الرسم البياني المعدلة حسب طلبك مع زيادة حجم خط الليبل
const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  interaction: {
    mode: 'index',
    intersect: false,
  },
  plugins: {
    legend: {
      labels: {
        boxWidth: 0,
        boxHeight: 0,
        font: {
          size: 22, // حجم الخط أكبر
          weight: 'bold',
        },
        color: '#38bdf8',
      },
      position: 'top',
    },
    tooltip: {
      enabled: true,
      backgroundColor: 'rgba(0,0,0,0.7)',
      titleFont: { size: 16 },
      bodyFont: { size: 14 },
      padding: 10,
      cornerRadius: 6,
    },
    title: {
      display: true,
      text: 'رسم بياني متطور لأداء النظام شهرياً',
      color: 'rgb(250,126,77)',
      font: { size: 18, weight: 'bold' },
      padding: { top: 10, bottom: 20 },
    },
  },
  scales: {
    x: {
      ticks: { color: '#ccc', font: { size: 14 } },
      grid: { color: 'rgba(255,255,255,0.1)' },
    },
    y: {
      beginAtZero: true,
      max: Math.max(...props.data) + 20,
      ticks: { color: '#ccc', font: { size: 14 } },
      grid: { color: 'rgba(255,255,255,0.1)' },
    },
  },
};
</script>

<template>
  <div class="chart-container" style="height: 350px;">
    <Line :data="chartData" :options="chartOptions" />
  </div>
</template>

<style scoped>
.chart-container {
  position: relative;
  width: 100%;
}
</style>
