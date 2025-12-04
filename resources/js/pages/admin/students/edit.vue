<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  student: { type: Object, required: true },
})

const form = useForm({
  name: props.student.name,
  email: props.student.email,
  password: '',
  password_confirmation: '',
  photos: [],
})

const photoPreviews = ref([])

const handlePhotoUpload = (event) => {
  form.photos = Array.from(event.target.files)
  photoPreviews.value = []

  if (form.photos.length) {
    form.photos.forEach(file => {
      const reader = new FileReader()
      reader.onload = (e) => photoPreviews.value.push(e.target.result)
      reader.readAsDataURL(file)
    })
  }
}

const updateStudent = () => {
  form.post(route('admin.students.update', props.student.id))
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="تعديل بيانات الطالب" />

    <div class="max-w-2xl mx-auto mt-10 p-10 bg-white rounded-3xl shadow-2xl border border-indigo-100 form-card-prominent">
      
      <div class="flex items-center mb-8 border-b pb-4 border-indigo-200">
        <i class="fas fa-user-edit text-3xl text-indigo-700 mr-3"></i>
        <h2 class="text-3xl font-extrabold text-gray-900 leading-tight">تعديل بيانات الطالب</h2>
      </div>

      <form @submit.prevent="updateStudent" class="space-y-6">
        
        <!-- الاسم -->
        <div>
          <label for="name" class="block mb-2 font-bold text-gray-800">اسم الطالب</label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            class="w-full input-field-prominent"
            required
          />
          <div v-if="form.errors.name" class="text-red-600 text-sm mt-1 font-semibold">{{ form.errors.name }}</div>
        </div>

        <!-- البريد الإلكتروني -->
        <div>
          <label for="email" class="block mb-2 font-bold text-gray-800">البريد الإلكتروني</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            class="w-full input-field-prominent"
            required
          />
          <div v-if="form.errors.email" class="text-red-600 text-sm mt-1 font-semibold">{{ form.errors.email }}</div>
        </div>

        <!-- كلمة المرور -->
        <div>
          <label for="password" class="block mb-2 font-bold text-gray-800">كلمة المرور (اختياري)</label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            class="w-full input-field-prominent"
            placeholder="اتركه فارغًا إن لم ترغب في تغييره"
          />
        </div>

        <!-- تأكيد كلمة المرور -->
        <div>
          <label for="password_confirmation" class="block mb-2 font-bold text-gray-800">تأكيد كلمة المرور</label>
          <input
            id="password_confirmation"
            v-model="form.password_confirmation"
            type="password"
            class="w-full input-field-prominent"
            placeholder="أعد كتابة كلمة المرور"
          />
        </div>

        <!-- الصور -->
        <div>
          <label for="photos" class="block mb-2 font-bold text-gray-800">تحديث صور الطالب (اختياري)</label>
          <input
            id="photos"
            type="file"
            multiple
            accept="image/*"
            @change="handlePhotoUpload"
            class="w-full input-field-prominent cursor-pointer"
          />
        </div>

        <!-- معاينة الصور -->
        <div v-if="photoPreviews.length > 0" class="mt-6 grid grid-cols-3 gap-4">
          <div v-for="(preview, index) in photoPreviews" :key="index" class="relative">
            <img :src="preview" class="h-24 w-24 object-cover rounded-xl border-2 border-indigo-200 shadow-md" />
          </div>
        </div>

        <!-- زر الحفظ -->
        <button
          type="submit"
          :disabled="form.processing"
          class="w-full py-4 mt-6 text-xl font-extrabold rounded-xl transition-all duration-300 transform hover:-translate-y-1 prominent-submit-button"
        >
          <span v-if="form.processing">جارٍ التحديث...</span>
          <span v-else>💾 حفظ التعديلات</span>
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
  @apply rounded-xl shadow-md px-4 py-3 transition-all duration-300 bg-white;
  border: 2px solid #D1D5DB;
  font-size: 1rem;
}
.input-field-prominent:focus {
  @apply ring-0 border-transparent;
  box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.4), inset 0 1px 3px rgba(0, 0, 0, 0.1);
  border-color: #4F46E5;
}
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
