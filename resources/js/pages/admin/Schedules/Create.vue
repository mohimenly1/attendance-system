<script setup>
/*
  resources/js/Pages/admin/Schedules/Create.vue
  نفس البنية والوظائف كما هي — فقط حسّنا "التنسيق" ليتطابق مع أسلوب صفحة إنشاء المستخدم.
*/

import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

// ✅ نستقبل القوائم الجاهزة من الكنترولر (courses / teachers / classrooms / days)
const props = defineProps({
  courses: { type: Array, default: () => [] },     // [{ id,name }]
  teachers: { type: Array, default: () => [] },    // [{ id,name }]
  classrooms: { type: Array, default: () => [] },  // [{ id,name }]
  days: { type: Array, default: () => ['Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday'] },
})

const form = useForm({
  course_id: '',
  teacher_id: '',       // اختياري: لو فاضي سنعتمد معلّم المادة
  classroom_id: '',     // إلزامي
  day_of_week: '',
  start_time: '',
  end_time: '',
})

// simple local error for time ordering
const localTimeError = ref('')

// small helper to build URL if Ziggy not available
function urlFor(name, params = null) {
  try {
    // eslint-disable-next-line no-undef
    return typeof route === 'function' ? route(name, params) : '/admin/schedules'
  } catch (e) {
    return '/admin/schedules'
  }
}

/** normalizeTime: Ensure time string is HH:MM (slice first 5 chars). */
function normalizeTime(value) {
  if (!value) return ''
  const s = String(value).trim()
  return s.length >= 5 ? s.slice(0, 5) : s
}

/** compareTimes: returns true if end > start (both 'HH:MM') */
function compareTimes(start, end) {
  if (!start || !end) return true // let server handle required checks
  const toMinutes = t => {
    const [h, m] = String(t).split(':').map(v => parseInt(v, 10) || 0)
    return h * 60 + m
  }
  return toMinutes(end) > toMinutes(start)
}

function submit() {
  form.start_time = normalizeTime(form.start_time)
  form.end_time = normalizeTime(form.end_time)

  if (!compareTimes(form.start_time, form.end_time)) {
    localTimeError.value = 'End time must be later than start time.'
    return
  }
  localTimeError.value = ''

  const target = urlFor('admin.schedules.store')
  form.post(target)
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Create Schedule" />

    <div class="max-w-2xl mx-auto mt-10 p-10 bg-white rounded-3xl shadow-2xl border border-indigo-100 form-card-prominent">
      <!-- Header -->
      <div class="flex items-center mb-8 border-b pb-4 border-indigo-200">
        <i class="fas fa-calendar-plus text-3xl text-indigo-700 mr-3"></i>
        <h2 class="text-3xl font-extrabold text-gray-900 leading-tight">Create Schedule</h2>
        <div class="ml-auto">
          <Link href="/admin/schedules" class="text-sm text-indigo-700 hover:underline">Back to list</Link>
        </div>
      </div>

      <!-- Form -->
      <form @submit.prevent="submit" class="space-y-6">
        <!-- Course -->
        <div>
          <label class="block mb-2 font-bold text-gray-800">Course</label>
          <select v-model="form.course_id" class="w-full input-field-prominent select-field-prominent">
            <option value="" disabled>Select course</option>
            <option v-for="c in props.courses" :key="c.id" :value="c.id">{{ c.name }}</option>
          </select>
          <div v-if="form.errors.course_id" class="text-red-600 text-sm mt-1 font-semibold">{{ form.errors.course_id }}</div>
        </div>

        <!-- Teacher (optional) -->
        <div>
          <label class="block mb-2 font-bold text-gray-800">Teacher (optional)</label>
          <select v-model="form.teacher_id" class="w-full input-field-prominent select-field-prominent">
            <option value="">Use course’s teacher</option>
            <option v-for="t in props.teachers" :key="t.id" :value="t.id">{{ t.name }}</option>
          </select>
          <div v-if="form.errors.teacher_id" class="text-red-600 text-sm mt-1 font-semibold">{{ form.errors.teacher_id }}</div>
        </div>

        <!-- Classroom -->
        <div>
          <label class="block mb-2 font-bold text-gray-800">Classroom</label>
          <select v-model="form.classroom_id" class="w-full input-field-prominent select-field-prominent">
            <option value="" disabled>Select classroom</option>
            <option v-for="r in props.classrooms" :key="r.id" :value="r.id">{{ r.name }}</option>
          </select>
          <div v-if="form.errors.classroom_id" class="text-red-600 text-sm mt-1 font-semibold">{{ form.errors.classroom_id }}</div>
        </div>

        <!-- Day -->
        <div>
          <label class="block mb-2 font-bold text-gray-800">Day</label>
          <select v-model="form.day_of_week" class="w-full input-field-prominent select-field-prominent">
            <option value="" disabled>Select day</option>
            <option v-for="d in props.days" :key="d" :value="d">{{ d }}</option>
          </select>
          <div v-if="form.errors.day_of_week" class="text-red-600 text-sm mt-1 font-semibold">{{ form.errors.day_of_week }}</div>
        </div>

        <!-- Times -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block mb-2 font-bold text-gray-800">Start time</label>
            <input v-model="form.start_time" type="time" class="w-full input-field-prominent" />
            <div v-if="form.errors.start_time" class="text-red-600 text-sm mt-1 font-semibold">{{ form.errors.start_time }}</div>
          </div>
          <div>
            <label class="block mb-2 font-bold text-gray-800">End time</label>
            <input v-model="form.end_time" type="time" class="w-full input-field-prominent" />
            <div v-if="form.errors.end_time" class="text-red-600 text-sm mt-1 font-semibold">{{ form.errors.end_time }}</div>
          </div>
        </div>

        <!-- Local time error -->
        <div v-if="localTimeError" class="text-red-600 text-sm font-semibold">
          {{ localTimeError }}
        </div>

        <!-- Submit -->
        <button
          type="submit"
          :disabled="form.processing"
          class="w-full py-4 mt-2 text-xl font-extrabold rounded-xl transition-all duration-300 transform hover:-translate-y-1 prominent-submit-button"
        >
          <span v-if="form.processing">Saving...</span>
          <span v-else>Create Schedule 🚀</span>
        </button>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* -------------------------- */
/* Prominent Card & Field Styling (منسوبة لنفس أسلوب صفحة إنشاء المستخدم) */
/* -------------------------- */

/* بطاقة النموذج */
.form-card-prominent {
  box-shadow: 0 15px 35px rgba(49, 46, 129, 0.2), 0 5px 15px rgba(0, 0, 0, 0.05);
}

/* حقول الإدخال */
.input-field-prominent {
  /* Tailwind utilities via @apply */
  @apply rounded-xl shadow-md px-4 py-3 transition-all duration-300 bg-white;
  border: 2px solid #D1D5DB; /* Gray-300 */
  font-size: 1rem;
}

.input-field-prominent:focus {
  @apply ring-0 border-transparent;
  box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.4), inset 0 1px 3px rgba(0, 0, 0, 0.1); /* Indigo-400 */
  border-color: #4F46E5; /* Indigo-600 */
}

/* قائمة الاختيار */
.select-field-prominent {
  appearance: none;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='none' stroke='%234F46E5'%3e%3cpath d='M7 7l3-3 3 3m0 6l-3 3-3-3' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 1rem center;
  background-size: 1em;
  padding-right: 3rem;
  border: 2px solid #D1D5DB;
  background-color: #ffffff;
}

/* زر الإرسال البارز */
.prominent-submit-button {
  background: linear-gradient(90deg, #4F46E5 0%, #3B82F6 100%); /* Indigo-600 to Blue-500 */
  color: white;
  box-shadow: 0 8px 25px rgba(79, 70, 229, 0.6);
  border: none;
}

.prominent-submit-button:hover {
  background: linear-gradient(90deg, #3730A3 0%, #1D4ED8 100%); /* Indigo-800 to Blue-700 */
  box-shadow: 0 10px 30px rgba(79, 70, 229, 0.8);
}
</style>
