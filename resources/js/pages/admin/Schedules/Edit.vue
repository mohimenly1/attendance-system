<script setup>
/*
  resources/js/Pages/admin/Schedules/Edit.vue
  نفس تنسيق Create.vue — بدون تغيير المنطق
*/

import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  schedule: { type: Object, required: true },
  courses: { type: Array, default: () => [] },
  teachers: { type: Array, default: () => [] },
  classrooms: { type: Array, default: () => [] },
})

const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']

const form = useForm({
  course_id: props.schedule.course_id ?? '',
  teacher_id: props.schedule.teacher_id ?? '',
  classroom_id: props.schedule.classroom_id ?? '',
  day_of_week: props.schedule.day_of_week ?? '',
  start_time: props.schedule.start_time ?? '',
  end_time: props.schedule.end_time ?? '',
})

const localTimeError = ref('')

function normalizeTime(value) {
  if (!value) return ''
  return String(value).slice(0, 5)
}

function compareTimes(start, end) {
  if (!start || !end) return true
  const toMin = t => {
    const [h, m] = t.split(':').map(Number)
    return h * 60 + m
  }
  return toMin(end) > toMin(start)
}

function submit() {
  form.start_time = normalizeTime(form.start_time)
  form.end_time   = normalizeTime(form.end_time)

  if (!compareTimes(form.start_time, form.end_time)) {
    localTimeError.value = 'End time must be later than start time.'
    return
  }
  localTimeError.value = ''

  form.put(`/admin/schedules/${props.schedule.id}`)
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Edit Schedule" />

    <div class="max-w-2xl mx-auto mt-10 p-10 bg-[#1e293b] rounded-3xl shadow-2xl border border-indigo-100 form-card-prominent">
      <!-- Header -->
      <div class="flex items-center mb-8 border-b pb-4 border-indigo-200">
        <i class="fas fa-pen text-3xl text-indigo-700 mr-3"></i>
        <h2 class="text-3xl font-extrabold text-white leading-tight">
          Edit Schedule
        </h2>
        <div class="ml-auto">
          <Link href="/admin/schedules" class="text-sm text-indigo-700 hover:underline">
            Back to list
          </Link>
        </div>
      </div>

      <!-- Form -->
      <form @submit.prevent="submit" class="space-y-6">
        <!-- Course -->
        <div>
          <label class="block mb-2 font-bold text-gray-300">Course</label>
          <select v-model="form.course_id" class="w-full input-field-prominent select-field-prominent">
            <option disabled value="">Select course</option>
            <option v-for="c in courses" :key="c.id" :value="c.id">
              {{ c.name }}
            </option>
          </select>
          <div v-if="form.errors.course_id" class="text-red-600 text-sm mt-1 font-semibold">
            {{ form.errors.course_id }}
          </div>
        </div>

        <!-- Teacher -->
        <div>
          <label class="block mb-2 font-bold text-gray-300">Teacher</label>
          <select v-model="form.teacher_id" class="w-full input-field-prominent select-field-prominent">
            <option value="">Use course’s teacher</option>
            <option v-for="t in teachers" :key="t.id" :value="t.id">
              {{ t.name }}
            </option>
          </select>
          <div v-if="form.errors.teacher_id" class="text-red-600 text-sm mt-1 font-semibold">
            {{ form.errors.teacher_id }}
          </div>
        </div>

        <!-- Classroom -->
        <div>
          <label class="block mb-2 font-bold text-gray-300">Classroom</label>
          <select v-model="form.classroom_id" class="w-full input-field-prominent select-field-prominent">
            <option disabled value="">Select classroom</option>
            <option v-for="r in classrooms" :key="r.id" :value="r.id">
              {{ r.name }}
            </option>
          </select>
          <div v-if="form.errors.classroom_id" class="text-red-600 text-sm mt-1 font-semibold">
            {{ form.errors.classroom_id }}
          </div>
        </div>

        <!-- Day -->
        <div>
          <label class="block mb-2 font-bold text-gray-300">Day</label>
          <select v-model="form.day_of_week" class="w-full input-field-prominent select-field-prominent">
            <option disabled value="">Select day</option>
            <option v-for="d in days" :key="d" :value="d">
              {{ d }}
            </option>
          </select>
          <div v-if="form.errors.day_of_week" class="text-red-600 text-sm mt-1 font-semibold">
            {{ form.errors.day_of_week }}
          </div>
        </div>

        <!-- Times -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block mb-2 font-bold text-gray-300">Start time</label>
            <input type="time" v-model="form.start_time" class="w-full input-field-prominent" />
          </div>
          <div>
            <label class="block mb-2 font-bold text-gray-300">End time</label>
            <input type="time" v-model="form.end_time" class="w-full input-field-prominent" />
          </div>
        </div>

        <div v-if="localTimeError" class="text-red-600 text-sm font-semibold">
          {{ localTimeError }}
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 pt-2">
          <button
            type="submit"
            :disabled="form.processing"
            class="flex-1 py-4 text-lg font-extrabold rounded-xl transition-all duration-300 transform hover:-translate-y-1 prominent-submit-button"
          >
            <span v-if="form.processing">Updating...</span>
            <span v-else>Update Schedule ✏️</span>
          </button>

          <Link
            href="/admin/schedules"
            class="flex-1 py-4 text-lg font-extrabold rounded-xl border border-gray-300 text-gray-100 bg-transparent hover:bg-slate-800 transition-all duration-300 transform hover:-translate-y-1 text-center"
          >
            Cancel
          </Link>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.form-card-prominent {
  box-shadow: 0 15px 35px rgba(49,46,129,.2), 0 5px 15px rgba(0,0,0,.05);
}

.input-field-prominent {
  @apply rounded-xl shadow-md px-4 py-3 transition-all duration-300 bg-[#1e293b];
  border: 2px solid #D1D5DB;
}

.input-field-prominent:focus {
  @apply ring-0 border-transparent;
  box-shadow: 0 0 0 4px rgba(99,102,241,.4);
}

.select-field-prominent {
  appearance: none;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='none' stroke='%234F46E5'%3e%3cpath d='M7 7l3-3 3 3m0 6l-3 3-3-3' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 1rem center;
  background-size: 1em;
  padding-right: 3rem;
  background-color: #1e293b;
}

.prominent-submit-button {
  background: linear-gradient(90deg,#4F46E5 0%,#3B82F6 100%);
  color: #fff;
  box-shadow: 0 8px 25px rgba(79,70,229,.6);
}

.prominent-submit-button:hover {
  background: linear-gradient(90deg,#3730A3 0%,#1D4ED8 100%);
}
</style>
