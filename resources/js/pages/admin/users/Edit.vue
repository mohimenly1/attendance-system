<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    user: Object,
});

// نموذج البيانات
const form = useForm({
    name: props.user.name,
    email: props.user.email,
    role: props.user.role,
});

// رسائل النجاح والخطأ
const successMessage = ref('');
const errorMessage = ref('');
const emailError = ref('');

// دالة التحقق من البريد الإلكتروني وتحديث البيانات
function submit() {
    // عرض رسالة النجاح فورًا عند بداية الحفظ
    successMessage.value = 'تم حفظ التعديلات بنجاح!';

    // تحقق من أن جميع الحقول غير فارغة
    if (!form.name || !form.email || !form.role) {
        errorMessage.value = 'جميع الحقول مطلوبة!';
        successMessage.value = ''; // إخفاء رسالة النجاح إذا كانت الحقول فارغة
        return;
    }

    // إذا كان البريد الإلكتروني لم يتغير، نقوم بتحديث البيانات مباشرةً
    if (form.email === props.user.email) {
        emailError.value = ''; // إذا كان البريد نفسه، لا يظهر التحذير
        return updateUser();
    }

    // تحقق من وجود البريد الإلكتروني في قاعدة البيانات
    axios.get(route('admin.users.checkEmail', { email: form.email }))
        .then(response => {
            if (response.data.exists) {
                emailError.value = 'البريد الإلكتروني مستخدم من قبل!';
                successMessage.value = ''; // إخفاء رسالة النجاح إذا كان البريد مكرر
            } else {
                emailError.value = ''; // إذا لم يكن البريد مكررًا
                updateUser();  // إرسال البيانات إذا لم يكن البريد مكررًا
            }
        })
        .catch(error => {
            console.error(error);
            emailError.value = 'حدث خطأ أثناء التحقق من البريد الإلكتروني!';
            successMessage.value = ''; // إخفاء رسالة النجاح في حال حدوث خطأ
        });
}

// دالة تحديث البيانات
function updateUser() {
    form.put(route('admin.users.update', props.user.id), {
        onFinish: () => {
            successMessage.value = 'تم حفظ التعديلات بنجاح!';
        },
    });
}
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Edit User" />

        <!-- رسالة النجاح -->
        <div v-if="successMessage" class="fixed top-10 left-1/2 transform -translate-x-1/2 bg-green-500 text-white py-3 px-6 rounded-lg shadow-lg flex items-center justify-center w-full max-w-lg z-50">
            <i class="fas fa-check-circle mr-2"></i>
            <span>{{ successMessage }}</span>
        </div>

        <!-- رسالة الخطأ إذا تم ترك الحقول فارغة -->
        <div v-if="errorMessage" class="fixed top-10 left-1/2 transform -translate-x-1/2 bg-red-500 text-white py-3 px-6 rounded-lg shadow-lg flex items-center justify-center w-full max-w-lg z-50">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span>{{ errorMessage }}</span>
        </div>

        <!-- رسالة الخطأ إذا كان البريد الإلكتروني مستخدم سابقًا -->
        <div v-if="emailError" class="fixed top-10 left-1/2 transform -translate-x-1/2 bg-yellow-500 text-white py-3 px-6 rounded-lg shadow-lg flex items-center justify-center w-full max-w-lg z-50">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span>{{ emailError }}</span>
        </div>

        <div class="max-w-2xl mx-auto mt-20 p-10 bg-[#1e293b] rounded-3xl shadow-2xl border border-indigo-100 form-card-prominent">

            <div class="flex items-center mb-8 border-b pb-4 border-indigo-200">
                <i class="fas fa-user-edit text-3xl text-indigo-700 mr-3"></i>
                <h2 class="text-3xl font-extrabold text-white leading-tight">Edit User</h2>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <label for="name" class="block mb-2 font-bold text-gray-300">Name</label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="w-full input-field-prominent"
                        required
                    />
                    <div v-if="form.errors.name" class="text-red-600 text-sm mt-1 font-semibold">{{ form.errors.name }}</div>
                </div>

                <div>
                    <label for="email" class="block mb-2 font-bold text-gray-300">Email</label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="w-full input-field-prominent"
                        required
                    />
                    <div v-if="form.errors.email" class="text-red-600 text-sm mt-1 font-semibold">{{ form.errors.email }}</div>
                    <div v-if="emailError" class="text-yellow-600 text-sm mt-1 font-semibold">{{ emailError }}</div>
                </div>

                <div>
                    <label for="role" class="block mb-2 font-bold text-gray-300">Role</label>
                    <select
                        id="role"
                        v-model="form.role"
                        class="w-full input-field-prominent select-field-prominent"
                        required
                    >
                        <option value="admin">Admin</option>
                        <option value="teacher">Teacher</option>
                        <option value="student">Student</option>
                    </select>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full py-4 mt-6 text-xl font-extrabold rounded-xl transition-all duration-300 transform hover:-translate-y-1 prominent-blue-button"
                >
                    <span v-if="form.processing">Saving...</span>
                    <span v-else>Save Changes 💾</span>
                </button>
            </form>

        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* -------------------------- */
/* Prominent Card & Field Styling */
/* -------------------------- */

/* Card style */
.form-card-prominent {
    box-shadow: 0 15px 35px rgba(49, 46, 129, 0.2), 0 5px 15px rgba(0, 0, 0, 0.05); /* Strong shadow */
}

/* Input field style */
.input-field-prominent {
    @apply rounded-xl shadow-md px-4 py-3 transition-all duration-300 bg-[#1e293b];
    border: 2px solid #D1D5DB; /* Gray-300 */
    font-size: 1rem;
}

/* Focus effect */
.input-field-prominent:focus {
    @apply ring-0 border-transparent;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.4), inset 0 1px 3px rgba(0, 0, 0, 0.1); /* Indigo-400 */
    border-color: #4F46E5; /* Indigo-600 */
}

/* Select field style */
.select-field-prominent {
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='none' stroke='%234F46E5'%3e%3cpath d='M7 7l3-3 3 3m0 6l-3 3-3-3' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 1em;
    padding-right: 3rem;
    border: 2px solid #D1D5DB;
    background-color: #1e293b;
}

/* Submit button style */
.prominent-blue-button {
    background: linear-gradient(90deg, #4F46E5 0%, #3B82F6 100%); /* Indigo-600 to Blue-500 */
    color: white;
    box-shadow: 0 8px 25px rgba(79, 70, 229, 0.6);
    border: none;
}

.prominent-blue-button:hover {
    background: linear-gradient(90deg, #3730A3 0%, #1D4ED8 100%); /* Indigo-800 to Blue-700 */
    box-shadow: 0 10px 30px rgba(79, 70, 229, 0.8);
}

/* ---------------------------- */
/* Styling for success and error messages */
/* ---------------------------- */
.fixed {
    position: fixed;
}

.bg-green-500 {
    background-color: #48bb78; /* Green background */
}

.bg-red-500 {
    background-color: #f56565; /* Red background for error */
}

.bg-yellow-500 {
    background-color: #fbbf24; /* Yellow background for warning */
}

.text-white {
    color: white;
}

.py-3 {
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
}

.px-6 {
    padding-left: 1.5rem;
    padding-right: 1.5rem;
}

.rounded-lg {
    border-radius: 0.5rem;
}

.shadow-lg {
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

.z-50 {
    z-index: 50;
}

.flex {
    display: flex;
}

.items-center {
    align-items: center;
}

.justify-center {
    justify-content: center;
}

.w-full {
    width: 100%;
}

.max-w-lg {
    max-width: 32rem;
}

.mr-2 {
    margin-right: 0.5rem;
}
</style>
