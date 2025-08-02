<script setup>
import { computed } from 'vue';
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import Dropdown from '@/components/Dropdown.vue';
import DropdownLink from '@/components/DropdownLink.vue';
import NavLink from '@/components/NavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

// Access user role from shared props
const page = usePage();
const userRole = computed(() => page.props.auth.user.role);

// Define navigation links for each role
const adminLinks = [
    { route: 'admin.dashboard', label: 'Dashboard' },
    { route: 'admin.users.index', label: 'User Management' },
    { route: 'admin.courses.index', label: 'Course Management' },
];

const teacherLinks = [
    // Add teacher-specific routes here later
    { route: 'teacher.dashboard', label: 'المواد الدراسية' },
    { route: 'teacher.courses.create', label: 'اضافة مادة جديدة' },
    { route: 'teacher.students.create', label: 'إضافة طالب جديد' }, // The new link
];

const studentLinks = [
    // Add student-specific routes here later
    { route: 'student.dashboard', label: 'Dashboard' },
    // { route: 'student.courses', label: 'My Courses' },
];
</script>

<template>
    <div class="flex h-screen bg-gray-100">
        <aside class="w-64 flex-shrink-0 bg-gray-800 text-white flex flex-col">
            <div class="h-16 flex items-center justify-center">
                <Link :href="route('admin.dashboard')">
                    <ApplicationLogo class="block h-9 w-auto" />
                </Link>
            </div>
            <nav class="flex-grow px-2 py-4">
                <div v-if="userRole === 'admin'">
                    <NavLink v-for="link in adminLinks" :key="link.route" :href="route(link.route)" :active="route().current(link.route)">
                        {{ link.label }}
                    </NavLink>
                </div>

                <div v-if="userRole === 'teacher'">
                    <NavLink v-for="link in teacherLinks" :key="link.route" :href="route(link.route)" :active="route().current(link.route)">
                        {{ link.label }}
                    </NavLink>
                </div>

                <div v-if="userRole === 'student'">
                    <NavLink v-for="link in studentLinks" :key="link.route" :href="route(link.route)" :active="route().current(link.route)">
                        {{ link.label }}
                    </NavLink>
                </div>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm flex justify-between items-center px-6 py-2">
                <div>
                     <slot name="header" />
                </div>

                <div class="relative">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button type="button" class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none">
                                {{ $page.props.auth.user.name }}
                                <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </template>

                        <template #content>
                            <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                            <DropdownLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-6 py-8">
                     <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
/* Custom styles for NavLink in sidebar */
a.inline-flex {
    width: 100%;
    padding: 0.75rem 1rem;
    margin-bottom: 0.25rem;
    border-radius: 0.375rem;
    color: #d1d5db; /* gray-300 */
    transition: background-color 0.2s, color 0.2s;
}

a.inline-flex:hover {
    background-color: #4b5563; /* gray-600 */
    color: #ffffff;
}

/* Style for active link */
a.inline-flex[data-active="true"] {
    background-color: #4f46e5; /* indigo-600 */
    color: #ffffff;
}
</style>