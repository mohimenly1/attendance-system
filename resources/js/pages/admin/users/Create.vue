<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    role: 'student', 
});

function submit() {
    form.post(route('admin.users.store'));
}
</script>

<template>
    <AuthenticatedLayout>
        <Head title="إضافة مستخدم" />

        <div class="max-w-2xl mx-auto mt-10 p-10 bg-white rounded-3xl shadow-2xl border border-indigo-100 form-card-prominent">
            
            <div class="flex items-center mb-8 border-b pb-4 border-indigo-200">
                <i class="fas fa-user-plus text-3xl text-indigo-700 mr-3"></i>
                <h2 class="text-3xl font-extrabold text-gray-900 leading-tight">إضافة مستخدم جديد</h2>
            </div>
            
            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <label for="name" class="block mb-2 font-bold text-gray-800">الاسم</label>
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

                <div>
                    <label for="password" class="block mb-2 font-bold text-gray-800">كلمة المرور</label>
                    <input 
                        id="password"
                        v-model="form.password" 
                        type="password" 
                        class="w-full input-field-prominent" 
                        required
                    />
                    <div v-if="form.errors.password" class="text-red-600 text-sm mt-1 font-semibold">{{ form.errors.password }}</div>
                </div>

                <div>
                    <label for="role" class="block mb-2 font-bold text-gray-800">الدور</label>
                    <select 
                        id="role"
                        v-model="form.role" 
                        class="w-full input-field-prominent select-field-prominent"
                    >
                        <option value="admin">مشرف</option>
                        <option value="teacher">معلم</option>
                        <option value="student">طالب</option>
                    </select>
                </div>

                <button 
                    type="submit" 
                    :disabled="form.processing"
                    class="w-full py-4 mt-6 text-xl font-extrabold rounded-xl transition-all duration-300 transform hover:-translate-y-1 prominent-submit-button"
                >
                    <span v-if="form.processing">جاري الإضافة...</span>
                    <span v-else>إضافة المستخدم 🚀</span>
                </button>
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
    /* ظل قوي وأنيق لجعله بارزًا */
    box-shadow: 0 15px 35px rgba(49, 46, 129, 0.2), 0 5px 15px rgba(0, 0, 0, 0.05); /* ظل نيلي قوي */
}

/* تصميم حقول الإدخال (Input Fields) */
.input-field-prominent {
    /* أساسيات Tailwind */
    @apply rounded-xl shadow-md px-4 py-3 transition-all duration-300 bg-white;
    
    /* حدود داكنة وبارزة */
    border: 2px solid #D1D5DB; /* Gray-300 */
    font-size: 1rem;
}

/* تأثير التركيز (Focus Effect) - نيلي حاد */
.input-field-prominent:focus {
    /* إزالة الحدود الافتراضية والتركيز على الظل واللون */
    @apply ring-0 border-transparent; 
    
    /* حلقة تركيز نيلية قوية */
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.4), inset 0 1px 3px rgba(0, 0, 0, 0.1); /* Indigo-400 */
    border-color: #4F46E5; /* Indigo-600 */
}

/* تصميم قائمة الاختيار (Select Field) */
.select-field-prominent {
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='none' stroke='%234F46E5'%3e%3cpath d='M7 7l3-3 3 3m0 6l-3 3-3-3' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 1em; /* أيقونة أكبر */
    padding-right: 3rem; /* مساحة للأيقونة الأكبر */
    /* حدود داكنة لجعلها بارزة */
    border: 2px solid #D1D5DB; 
    background-color: #ffffff;
}


/* -------------------------- */
/* Submit Button Styling - الزر الأبرز */
/* -------------------------- */
.prominent-submit-button {
    /* تدرج لوني أكثر حيوية وتباينًا */
    background: linear-gradient(90deg, #4F46E5 0%, #3B82F6 100%); /* Indigo-600 to Blue-500 */
    color: white;
    /* ظل قوي جداً يجعله يبرز عن الصفحة */
    box-shadow: 0 8px 25px rgba(79, 70, 229, 0.6); 
    border: none;
}

.prominent-submit-button:hover {
    /* تدرج أغمق مع حركة طفيفة */
    background: linear-gradient(90deg, #3730A3 0%, #1D4ED8 100%); /* Indigo-800 to Blue-700 */
    box-shadow: 0 10px 30px rgba(79, 70, 229, 0.8);
}
</style>