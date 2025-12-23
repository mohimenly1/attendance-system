<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  photos: [],
});

const photoPreviews = ref([]);

// success message
const successMessage = ref('');

const handlePhotoUpload = (event) => {
  form.photos = Array.from(event.target.files);
  photoPreviews.value = [];

  if (form.photos.length) {
    form.photos.forEach(file => {
      const reader = new FileReader();
      reader.onload = (e) => {
        photoPreviews.value.push(e.target.result);
      };
      reader.readAsDataURL(file);
    });
  }
};

const submit = () => {
  form.post(route('admin.students.store'), {
    onSuccess: () => {
      successMessage.value = 'تمت إضافة الطالب بنجاح!';
      form.reset('name', 'email', 'password', 'password_confirmation', 'photos');
      photoPreviews.value = [];
    },
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Add New Student" />

    <!-- Success Toast -->
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

    <div class="max-w-2xl mx-auto mt-10 p-10 bg-[#1e293b] rounded-3xl shadow-2xl border border-indigo-100 form-card-prominent">

      <div class="flex items-center mb-8 border-b pb-4 border-indigo-200">
        <i class="fas fa-user-graduate text-3xl text-indigo-700 mr-3"></i>
        <h2 class="text-3xl font-extrabold text-white leading-tight">Add New Student</h2>
      </div>

      <form @submit.prevent="submit" class="space-y-6">

        <!-- Student Name -->
        <div>
          <label for="name" class="block mb-2 font-bold text-gray-300">Student Name</label>
          <input
            v-model="form.name"
            id="name"
            type="text"
            class="w-full input-field-prominent"
            required
            placeholder="Enter student's name"
          />
          <div v-if="form.errors.name" class="text-red-600 text-sm mt-1 font-semibold">{{ form.errors.name }}</div>
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block mb-2 font-bold text-gray-300">Email</label>
          <input
            v-model="form.email"
            id="email"
            type="email"
            class="w-full input-field-prominent"
            required
            placeholder="example@student.com"
          />
          <div v-if="form.errors.email" class="text-red-600 text-sm mt-1 font-semibold">{{ form.errors.email }}</div>
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block mb-2 font-bold text-gray-300">Password</label>
          <input
            v-model="form.password"
            id="password"
            type="password"
            class="w-full input-field-prominent"
            required
            placeholder="Enter password"
          />
          <div v-if="form.errors.password" class="text-red-600 text-sm mt-1 font-semibold">{{ form.errors.password }}</div>
        </div>

        <!-- Password Confirmation -->
        <div>
          <label for="password_confirmation" class="block mb-2 font-bold text-gray-300">Confirm Password</label>
          <input
            v-model="form.password_confirmation"
            id="password_confirmation"
            type="password"
            class="w-full input-field-prominent"
            required
            placeholder="Re-enter password"
          />
        </div>

        <!-- Upload Photos -->
        <div>
          <label for="photos" class="block mb-2 font-bold text-gray-300">Student Photos (You can select multiple images)</label>
          <input
            @change="handlePhotoUpload"
            id="photos"
            type="file"
            multiple
            accept="image/*"
            class="w-full input-field-prominent cursor-pointer"
          />
          <div v-if="form.errors.photos" class="text-red-600 text-sm mt-1 font-semibold">{{ form.errors.photos }}</div>
        </div>

        <!-- Photo Previews -->
        <div v-if="photoPreviews.length > 0" class="mt-6 grid grid-cols-3 gap-4">
          <div v-for="(preview, index) in photoPreviews" :key="index" class="relative">
            <img :src="preview" class="h-24 w-24 object-cover rounded-xl border-2 border-indigo-200 shadow-md" />
          </div>
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          :disabled="form.processing"
          class="w-full py-4 mt-6 text-xl font-extrabold rounded-xl transition-all duration-300 transform hover:-translate-y-1 prominent-submit-button"
        >
          <span v-if="form.processing">Creating account...</span>
          <span v-else>Create Student Account</span>
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
