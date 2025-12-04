<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

// ✅ استقبال البيانات من Laravel
const props = defineProps({
  course: { type: Object, required: true },
  teachers: { type: Array, required: true },
})

// ✅ إنشاء الفورم باستخدام useForm
const form = useForm({
  name: props.course.name,
  code: props.course.code,
  description: props.course.description,
  teacher_id: props.course.teacher_id,
})

// ✅ إرسال البيانات عند الحفظ
function updateCourse() {
  form.put(route('admin.courses.update', props.course.id))
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="تعديل المادة" />

    <div class="max-w-2xl mx-auto mt-10 p-10 bg-white rounded-3xl shadow-2xl border border-indigo-100 form-card-prominent">
      
      <div class="flex items-center mb-8 border-b pb-4 border-indigo-200">
        <i class="fas fa-edit text-3xl text-indigo-700 mr-3"></i>
        <h2 class="text-3xl font-extrabold text-gray-900 leading-tight">تعديل بيانات المادة</h2>
      </div>

      <form @submit.prevent="updateCourse" class="space-y-6">
        
        <!-- اسم المادة -->
        <div>
          <label class="block mb-2 font-bold text-gray-800">اسم المادة</label>
          <input
            v-model="form.name"
            type="text"
            class="w-full input-field-prominent"
            required
          />
          <div v-if="form.errors.name" class="text-red-600 text-sm mt-1 font-semibold">{{ form.errors.name }}</div>
        </div>

        <!-- كود المادة -->
        <div>
          <label class="block mb-2 font-bold text-gray-800">كود المادة</label>
          <input
            v-model="form.code"
            type="text"
            class="w-full input-field-prominent"
            required
          />
          <div v-if="form.errors.code" class="text-red-600 text-sm mt-1 font-semibold">{{ form.errors.code }}</div>
        </div>

        <!-- المعلّم -->
        <div>
          <label class="block mb-2 font-bold text-gray-800">المعلم المسؤول</label>
          <select
            v-model="form.teacher_id"
            class="w-full input-field-prominent select-field-prominent"
            required
          >
            <option disabled value="">اختر المعلم</option>
            <option v-for="t in props.teachers" :key="t.id" :value="t.id">
              {{ t.name }}
            </option>
          </select>
          <div v-if="form.errors.teacher_id" class="text-red-600 text-sm mt-1 font-semibold">{{ form.errors.teacher_id }}</div>
        </div>

        <!-- الوصف -->
        <div>
          <label class="block mb-2 font-bold text-gray-800">الوصف</label>
          <textarea
            v-model="form.description"
            rows="3"
            placeholder="اكتب وصفًا مختصرًا (اختياري)"
            class="w-full input-field-prominent"
          ></textarea>
        </div>

        <!-- الأزرار -->
        <div class="flex justify-between items-center mt-6">
          <Link
            :href="route('admin.courses.index')"
            class="bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-300 transition-all"
          >
            إلغاء
          </Link>

          <button
            type="submit"
            :disabled="form.processing"
            class="py-4 px-6 text-xl font-extrabold rounded-xl transition-all duration-300 transform hover:-translate-y-1 prominent-submit-button"
          >
            <span v-if="form.processing">جاري الحفظ...</span>
            <span v-else>تحديث المادة 💾</span>
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* -------------------------- */
/* Prominent Card & Field Styling */
/* -------------------------- */

/* تصميم بطاقة النموذج (Card Style) */
.form-card-prominent {
  box-shadow: 0 15px 35px rgba(49, 46, 129, 0.2), 0 5px 15px rgba(0, 0, 0, 0.05);
}

/* تصميم حقول الإدخال (Input Fields) */
.input-field-prominent {
  @apply rounded-xl shadow-md px-4 py-3 transition-all duration-300 bg-white;
  border: 2px solid #D1D5DB;
  font-size: 1rem;
}

/* تأثير التركيز (Focus Effect) */
.input-field-prominent:focus {
  @apply ring-0 border-transparent;
  box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.4), inset 0 1px 3px rgba(0, 0, 0, 0.1);
  border-color: #4F46E5;
}

/* قائمة المعلمين */
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

/* -------------------------- */
/* Submit Button Styling */
/* -------------------------- */
.prominent-submit-button {
  background: linear-gradient(90deg, #4F46E5 0%, #3B82F6 100%);
  color: white;
  box-shadow: 0 8px 25px rgba(79, 70, 229, 0.6);
  border: none;
}

.prominent-submit-button:hover {
  background: linear-gradient(90deg, #3730A3 0%, #1D4ED8 100%);
  box-shadow: 0 10px 30px rgba(79, 70, 229, 0.8);
}
</style>
