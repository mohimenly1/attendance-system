<script setup>
import { computed } from 'vue'
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  course: { type: Object, required: true },
  teachers: { type: Array, required: true },
  flash: {
    type: Object,
    default: () => ({}),
  },
})

// form data
const form = useForm({
  name: props.course.name,
  code: props.course.code,
  description: props.course.description,
  teacher_id: props.course.teacher_id,
})

// success message تأتي من السيرفر عبر flash.success
const successMessage = computed(() => props.flash?.success || '')

function updateCourse() {
  form.put(route('admin.courses.update', props.course.id))
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Edit Course" />

    <!-- Success bar like screenshot -->
    <div v-if="successMessage" class="w-full flex justify-center mt-6">
      <div
        class="w-full max-w-4xl bg-green-500 text-white px-8 py-4 rounded-3xl shadow-lg flex items-center justify-center"
      >
        <i class="fas fa-check-circle ml-3 text-xl"></i>
        <span class="text-lg font-bold">
          {{ successMessage }}
        </span>
      </div>
    </div>

    <div
      class="max-w-2xl mx-auto mt-10 p-10 bg-[#1e293b] rounded-3xl shadow-2xl border border-indigo-100 form-card-prominent"
    >
      <!-- Header -->
      <div class="flex items-center mb-8 border-b pb-4 border-indigo-200">
        <i class="fas fa-edit text-3xl text-indigo-700 mr-3"></i>
        <h2 class="text-3xl font-extrabold text-white leading-tight">
          Edit Course
        </h2>

        <div class="ml-auto">
          <Link
            :href="route('admin.courses.index')"
            class="text-sm text-indigo-300 hover:underline"
          >
            Back to list
          </Link>
        </div>
      </div>

      <!-- Form -->
      <form @submit.prevent="updateCourse" class="space-y-6">
        <!-- Name -->
        <div>
          <label for="name" class="block mb-2 font-bold text-gray-300">
            Course Name
          </label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            class="w-full input-field-prominent"
            required
          />
          <div
            v-if="form.errors.name"
            class="text-red-600 text-sm mt-1 font-semibold"
          >
            {{ form.errors.name }}
          </div>
        </div>

        <!-- Code -->
        <div>
          <label for="code" class="block mb-2 font-bold text-gray-300">
            Course Code
          </label>
          <input
            id="code"
            v-model="form.code"
            type="text"
            class="w-full input-field-prominent"
            required
          />
          <div
            v-if="form.errors.code"
            class="text-red-600 text-sm mt-1 font-semibold"
          >
            {{ form.errors.code }}
          </div>
        </div>

        <!-- Teacher -->
        <div>
          <label for="teacher_id" class="block mb-2 font-bold text-gray-300">
            Assigned Teacher
          </label>
          <select
            id="teacher_id"
            v-model="form.teacher_id"
            class="w-full input-field-prominent select-field-prominent"
            required
          >
            <option disabled value="">Select teacher</option>
            <option v-for="t in teachers" :key="t.id" :value="t.id">
              {{ t.name }}
            </option>
          </select>
          <div
            v-if="form.errors.teacher_id"
            class="text-red-600 text-sm mt-1 font-semibold"
          >
            {{ form.errors.teacher_id }}
          </div>
        </div>

        <!-- Description -->
        <div>
          <label for="description" class="block mb-2 font-bold text-gray-300">
            Description
          </label>
          <textarea
            id="description"
            v-model="form.description"
            rows="3"
            placeholder="Short description (optional)"
            class="w-full input-field-prominent"
          ></textarea>
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 pt-2">
          <button
            type="submit"
            :disabled="form.processing"
            class="flex-1 py-4 text-lg font-extrabold rounded-xl transition-all duration-300 transform hover:-translate-y-1 prominent-blue-button disabled:opacity-60 disabled:cursor-not-allowed"
          >
            <span v-if="form.processing">Saving...</span>
            <span v-else>Save Changes</span>
          </button>

          <Link
            :href="route('admin.courses.index')"
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
  box-shadow: 0 15px 35px rgba(49, 46, 129, 0.2),
    0 5px 15px rgba(0, 0, 0, 0.05);
}

.input-field-prominent {
  @apply rounded-xl shadow-md px-4 py-3 transition-all duration-300 bg-[#1e293b];
  border: 2px solid #d1d5db;
  font-size: 1rem;
}

.input-field-prominent:focus {
  @apply ring-0 border-transparent;
  box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.4),
    inset 0 1px 3px rgba(0, 0, 0, 0.1);
  border-color: #4f46e5;
}

.select-field-prominent {
  appearance: none;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='none' stroke='%234F46E5'%3e%3cpath d='M7 7l3-3 3 3m0 6l-3 3-3-3' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 1rem center;
  background-size: 1em;
  padding-right: 3rem;
  border: 2px solid #d1d5db;
  background-color: #1e293b;
}

.prominent-blue-button {
  background: linear-gradient(90deg, #4f46e5 0%, #3b82f6 100%);
  color: white;
  box-shadow: 0 8px 25px rgba(79, 70, 229, 0.6);
  border: none;
}

.prominent-blue-button:hover {
  background: linear-gradient(90deg, #3730a3 0%, #1d4ed8 100%);
  box-shadow: 0 10px 30px rgba(79, 70, 229, 0.8);
}
</style>
