<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

// Props from controller
const props = defineProps({
    users: Array,
});

// Reactive search query
const searchQuery = ref('');

// دالة حذف المستخدم
function deleteUser(id) {
    if (confirm('هل أنت متأكد من حذف هذا المستخدم؟ هذا الإجراء لا يمكن التراجع عنه.')) {
        router.delete(route('admin.users.destroy', id), {
            onSuccess: () => {
                alert('تم حذف المستخدم بنجاح');
            },
            onError: () => {
                alert('حدث خطأ أثناء حذف المستخدم');
            }
        });
    }
}

// فلترة المستخدمين حسب البحث (اسم أو إيميل)
const filteredUsers = computed(() => {
    if (!searchQuery.value) return props.users;
    const query = searchQuery.value.toLowerCase();
    return props.users.filter(user =>
        user.name.toLowerCase().includes(query) || user.email.toLowerCase().includes(query)
    );
});
</script>

<template>
    <Head title="User Management" />

    <AuthenticatedLayout>
        <div class="p-6 user-management-container">
            <div class="flex flex-col items-start mb-6">
                <!-- Title -->
                <h2 class="text-3xl font-semibold text-gradient tracking-wide mb-2">
                    User Management
                </h2>

                <!-- Subtitle or description below the title -->

            </div>

            <!-- Add User Button and Search Field -->
            <div class="flex justify-between items-center mb-4">
                <!-- Search Field -->
                <div class="flex-1 mr-4">
                    <input
                        type="text"
                        v-model="searchQuery"
                        placeholder="Search users..."
                        class="w-full border border-blue-200 rounded-lg py-2 px-4 bg-[#1e2a47] text-white placeholder:text-blue-200 focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-300 transition-all duration-300"
                    >
                </div>

                <!-- Add User Button -->
                <Link
                    :href="route('admin.users.create')"
                    class="bg-gradient-to-r from-indigo-500 to-blue-500 hover:from-indigo-600 hover:to-blue-600 text-white font-semibold py-2 px-6 rounded-lg flex items-center shadow-md transition-all duration-200"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add User
                </Link>
            </div>

            <!-- Table Section -->
            <div class="bg-[#1e2a47] rounded-xl shadow-lg overflow-hidden border border-blue-100">
                < <div class="overflow-x-auto w-full">
    <table class="min-w-[800px] w-full divide-y divide-blue-200">
                        <!-- Table Header -->
                        <thead class="bg-blue-600">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-blue-100 uppercase tracking-wider">NAME</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-blue-100 uppercase tracking-wider">EMAIL</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-blue-100 uppercase tracking-wider">ROLE</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-blue-100 uppercase tracking-wider">ACTIONS</th>
                            </tr>
                        </thead>

                        <!-- Table Body -->
                        <tbody class="bg-[#0f1b29] divide-y divide-blue-100">
                            <tr
                                v-for="user in filteredUsers"
                                :key="user.id"
                                class="hover:bg-blue-70over:shadow-md hover:scale-105 transition-all duration-200"
                            >
                                <td class="px-6 py-4 text-sm text-white">{{ user.name }}</td>
                                <td class="px-6 py-4 text-sm text-white">{{ user.email }}</td>

                                <td class="px-6 py-4 text-sm capitalize">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-medium"
                                        :class="{
                                            'bg-blue-100 text-blue-800': user.role === 'student',
                                            'bg-indigo-100 text-indigo-800': user.role === 'admin',
                                            'bg-teal-100 text-teal-800': user.role === 'teacher'
                                        }"
                                    >
                                        {{ user.role }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <!-- Edit Button -->
                                        <Link
                                            :href="route('admin.users.edit', user.id)"
                                            class="inline-flex items-center px-3 py-1.5 text-white text-sm font-medium rounded-lg transition duration-150 shadow-md modern-edit-button"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7-7l4 4m-4-4l4 4" />
                                            </svg>
                                            Edit
                                        </Link>

                                        <!-- Delete Button -->
                                        <button
                                            @click="deleteUser(user.id)"
                                            class="inline-flex items-center px-3 py-1.5 text-white text-sm font-medium rounded-lg transition duration-150 shadow-md modern-delete-button"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- No Users Found Message -->
                            <tr v-if="filteredUsers.length === 0">
                                <td colspan="4" class="px-6 py-8 text-center text-blue-400">
                                    <div class="flex flex-col items-center">
                                        <span class="text-4xl mb-2">🚫</span>
                                        <p class="text-lg">No users found.</p>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* -------------------------- */
/* Title Styling with Gradient and Effects */
/* -------------------------- */
.text-gradient {
    background-image: linear-gradient(to right, #4F46E5, #3B82F6); /* Gradient from Indigo to Blue */
    -webkit-background-clip: text; /* This property makes the gradient apply to text */
    color: transparent; /* Ensures the gradient is visible in the text */
}

.text-gradient:hover {
    background-image: linear-gradient(to right, #2563EB, #4338CA); /* Darker gradient on hover */
}

.text-gradient {
    font-size: 1.5rem; /* Increased font size for title */
    font-weight: 700; /* Bold text */
    text-transform: uppercase; /* Uppercase letters for a bold impact */
    letter-spacing: 2px; /* Adjusted letter spacing */
    text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.2); /* Shadow effect to add depth */
}

/* Subtitle Styling */
p {
    font-size: 1.125rem; /* Adjusted font size for the subtitle */
    color: #A0AEC0; /* Light gray color */
    margin-top: 0.5rem; /* Space between title and subtitle */
    font-weight: 300; /* Lighter weight for the Arabic text */
}

/* -------------------------- */
/* Modern Buttons Styling */
/* -------------------------- */
.modern-button {
    background-image: linear-gradient(to right, #4F46E5 0%, #3B82F6 100%);
    box-shadow: 0 4px 10px rgba(79, 70, 229, 0.4);
}
.modern-button:hover {
    background-image: linear-gradient(to right, #4338CA 0%, #2563EB 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(79, 70, 229, 0.5);
}

.modern-edit-button {
    background-image: linear-gradient(to right, #3B82F6 0%, #60A5FA 100%);
    box-shadow: 0 2px 5px rgba(59, 130, 246, 0.4);
}
.modern-edit-button:hover {
    background-image: linear-gradient(to right, #2563EB 0%, #3B82F6 100%);
}

.modern-delete-button {
    background-image: linear-gradient(to right, #EF4444 0%, #F87171 100%);
    box-shadow: 0 2px 5px rgba(239, 68, 68, 0.4);
}
.modern-delete-button:hover {
    background-image: linear-gradient(to right, #DC2626 0%, #EF4444 100%);
}

/* -------------------------- */
/* Table Styling */
/* -------------------------- */
.min-w-full thead {
    background-color: #3b82f6; /* Blue-500 */
    border-top-left-radius: 1rem;
    border-top-right-radius: 1rem;
}
.min-w-full thead th {
    color: #DBEAFE; /* Blue-100 */
}

.icon-gradient {
    color: #4f46e5; /* Indigo-600 */
}
</style>
