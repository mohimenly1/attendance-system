<script setup>
import { computed } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue'

// استلام flash و teachers كـ props من Inertia
const props = defineProps({
  flash: {
    type: Object,
    default: () => ({}),
  },
  teachers: {
    type: Array,
    default: () => [],
  },
})

// رسالة النجاح مباشرة من flash.success
const successMessage = computed(() => props.flash?.success || '')

// نموذج إضافة المقرر
const form = useForm({
  name: '',
  code: '',
  description: '',
  teacher_id: '',
  course_type: '',
})

// دالة الإرسال
const submit = () => {
  form.post(route('admin.courses.store'), {
    onSuccess: () => {
      // بعد نجاح الإرسال نفضي الحقول فقط
      form.reset('name', 'code', 'description', 'teacher_id', 'course_type')
    },
  })
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Add New Course" />

    <!-- تنبيه علوي ثابت عند نجاح الإضافة -->
    <div
      v-if="successMessage"
      class="fixed top-4 inset-x-0 z-50 flex justify-center px-4"
    >
      <div
        class="bg-emerald-500 text-white px-6 py-3 rounded-2xl shadow-lg border border-emerald-300/70 text-sm font-semibold"
      >
        {{ successMessage }}
      </div>
    </div>

    <div
      class="max-w-2xl mx-auto mt-10 p-10 bg-[#1e293b] rounded-3xl shadow-2xl border border-indigo-100 form-card-prominent"
    >
      <div class="flex items-center mb-8 border-b pb-4 border-indigo-200">
        <i class="fas fa-book-open text-3xl text-indigo-700 mr-3"></i>
        <h2 class="text-3xl font-extrabold text-white leading-tight">
          Add New Course
        </h2>
      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <!-- Course Name -->
        <div>
          <label for="name" class="block mb-2 font-bold text-gray-300">
            Course Name
          </label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            class="w-full input-field-prominent"
            placeholder="Enter course name"
            required
          />
          <div
            v-if="form.errors.name"
            class="text-red-600 text-sm mt-1 font-semibold"
          >
            {{ form.errors.name }}
          </div>
        </div>

        <!-- Course Code -->
        <div>
          <label for="code" class="block mb-2 font-bold text-gray-300">
            Course Code
          </label>
          <input
            id="code"
            v-model="form.code"
            type="text"
            class="w-full input-field-prominent"
            placeholder="Enter course code"
            required
          />
          <div
            v-if="form.errors.code"
            class="text-red-600 text-sm mt-1 font-semibold"
          >
            {{ form.errors.code }}
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
            placeholder="Write a brief description (optional)"
            class="w-full input-field-prominent"
          ></textarea>
        </div>

        <!-- Teacher -->
        <div>
          <label for="teacher_id" class="block mb-2 font-bold text-gray-300">
            Teacher
          </label>
          <select
            id="teacher_id"
            v-model="form.teacher_id"
            class="w-full input-field-prominent select-field-prominent"
            required
          >
            <option disabled value="">Select Teacher</option>
            <option
              v-for="teacher in teachers"
              :key="teacher.id"
              :value="teacher.id"
            >
              {{ teacher.name }}
            </option>
          </select>
          <div
            v-if="form.errors.teacher_id"
            class="text-red-600 text-sm mt-1 font-semibold"
          >
            {{ form.errors.teacher_id }}
          </div>
        </div>

        <!-- Course Type -->
        <div>
          <label for="course_type" class="block mb-2 font-bold text-gray-300">
            Course Type
          </label>
          <select
            id="course_type"
            v-model="form.course_type"
            class="w-full input-field-prominent select-field-prominent"
            required
          >
            <option disabled value="">Select Course Type</option>
            <option value="Math">Math</option>
            <option value="Science">Science</option>
            <option value="Programming">Programming</option>
            <option value="Literature">Literature</option>
          </select>
          <div
            v-if="form.errors.course_type"
            class="text-red-600 text-sm mt-1 font-semibold"
          >
            {{ form.errors.course_type }}
          </div>
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          :disabled="form.processing"
          class="w-full py-4 mt-6 text-xl font-extrabold rounded-xl transition-all duration-300 transform hover:-translate-y-1 prominent-submit-button disabled:opacity-60 disabled:cursor-not-allowed"
        >
          <span v-if="form.processing">Saving...</span>
          <span v-else>Save Course</span>
        </button>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.form-card-prominent {
  box-shadow: 0 15px 35px rgba(49, 46, 129, 0.2), 0 5px 15px rgba(0, 0, 0, 0.05);
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

.prominent-submit-button {
  background: linear-gradient(90deg, #4f46e5 0%, #3b82f6 100%);
  color: white;
  box-shadow: 0 8px 25px rgba(79, 70, 229, 0.6);
  border: none;
}

.prominent-submit-button:hover {
  background: linear-gradient(90deg, #3730a3 0%, #1d4ed8 100%);
  box-shadow: 0 10px 30px rgba(79, 70, 229, 0.8);
}
</style>
