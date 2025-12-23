<script setup>
import { computed } from 'vue'
import ApplicationLogo from '@/components/ApplicationLogo.vue'
import Dropdown from '@/components/Dropdown.vue'
import DropdownLink from '@/components/DropdownLink.vue'
import NavLink from '@/components/NavLink.vue'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const userRole = computed(() => page.props.auth.user.role)

const adminLinks = [
  { route: 'admin.dashboard', label: 'Dashboard', icon: 'fas fa-tachometer-alt' },
  { route: 'admin.users.index', label: 'User Management', icon: 'fas fa-users-cog' },
  { route: 'admin.courses.index', label: 'Course Management', icon: 'fas fa-book' },
  { route: 'admin.students.index', label: 'Student Management', icon: 'fas fa-user-graduate' },
  { route: 'admin.schedules.index', label: 'Schedule Management', icon: 'fas fa-calendar-alt' },
  { route: 'admin.attendance.index', label: 'Attendance', icon: 'fas fa-clipboard-check' },
]

const teacherLinks = [
  { route: 'teacher.dashboard', label: 'Dashboard', icon: 'fas fa-chalkboard' },


]

const studentLinks = [
  { route: 'student.dashboard', label: 'Dashboard', icon: 'fas fa-home' },
]

const getLinks = computed(() => {
  if (userRole.value === 'admin') return adminLinks
  if (userRole.value === 'teacher') return teacherLinks
  if (userRole.value === 'student') return studentLinks
  return []
})

const logoRoute = computed(() => {
  if (userRole.value === 'admin') return 'admin.dashboard'
  if (userRole.value === 'teacher') return 'teacher.dashboard'
  if (userRole.value === 'student') return 'student.dashboard'
  return 'student.dashboard'
})
</script>

<template>
  <div class="modern-layout flex h-screen">
    <!-- Sidebar -->
    <aside class="sidebar-gradient sidebar-glass flex w-64 flex-shrink-0 flex-col text-white shadow-2xl">
      <!-- Logo -->
      <div class="logo-section flex h-16 items-center justify-center border-b border-blue-700/50">
        <Link :href="route(logoRoute)">
          <ApplicationLogo class="block h-9 w-auto text-white/90" />
        </Link>
      </div>
      <!-- Nav -->
      <nav class="custom-scrollbar flex-grow overflow-y-auto px-4 py-6">
        <div class="space-y-2">
          <NavLink v-for="link in getLinks" :key="link.route" :href="route(link.route)" :active="route().current(link.route)" class="modern-nav-link">
            <i :class="[link.icon, 'w-5 text-center mr-3']"></i> {{ link.label }}
          </NavLink>
        </div>
      </nav>
    </aside>

    <!-- Main -->
    <div class="flex flex-1 flex-col overflow-hidden">
      <!-- TOP NAVBAR -->
      <header class="header-shadow sticky top-0 z-20 flex items-center justify-between bg-white px-6 py-3">
        <div class="flex items-center gap-2">
          <span class="hidden sm:inline text-sm font-semibold text-gray-700">Attendance System</span>
        </div>
        <!-- يمين: قائمة المستخدم -->
        <div class="relative">
          <Dropdown align="right" width="48">
            <template #trigger>
              <button type="button" class="inline-flex items-center rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-medium leading-4 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                {{ $page.props.auth.user.name }}
                <svg class="ms-2 -me-0.5 h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
            </template>
            <template #content>
              <DropdownLink :href="route('profile.edit')" class="text-gray-700 hover:bg-blue-50">
                <i class="fas fa-user-circle mr-2"></i> Profile
              </DropdownLink>
              <div class="my-1 border-t border-gray-100"></div>
              <DropdownLink :href="route('logout')" method="post" as="button" class="text-red-600 hover:bg-red-50">
                <i class="fas fa-sign-out-alt mr-2"></i> Log Out
              </DropdownLink>
            </template>
          </Dropdown>
        </div>
      </header>

      <!-- PAGE HEADER -->
      <section class="mx-auto w-full max-w-7xl px-6 pt-6">
        <div class="font-semibold text-lg text-gray-800">
          <slot name="header" />
        </div>
      </section>

      <!-- PAGE CONTENT -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto">
        <div class="container mx-auto max-w-7xl px-6 py-6">
          <slot />
        </div>
      </main>
    </div>
  </div>
</template>


<style scoped>
/* -------------------------- */
/* Root Variables for the Color Scheme */
/* -------------------------- */
:root {
  --bg-color: #0b1220;           /* الخلفية العامة */
  --second-bg-color: #0f1b2d;    /* خلفية للأقسام */
  --surface: #13233a;            /* خلفية البطاقات */

  --accent: #7de1ff;             /* اللون المتوهج */
}

/* -------------------------- */
/* Sidebar Styling */
/* -------------------------- */
.sidebar-gradient {
  background: linear-gradient(180deg, var(--bg-color) 0%, var(--second-bg-color) 100%);
  position: relative;
  border-right: 1px solid rgba(255, 255, 255, 0.1);
  padding-top: 2rem;
  padding-bottom: 2rem;
  padding-left: 1.5rem;
  transition: width 0.3s; /* smooth transition for width change */
}

.sidebar-glass {
  background: linear-gradient(180deg, #4F46E5 0%, );


}

.logo-section {
  padding-top: 1rem;
  padding-bottom: 1rem;
}

.modern-nav-link {
  display: flex;
  align-items: center;
  padding: 0.75rem; /* Adjusted padding */
  margin-bottom: 0.75rem;
  background-color: transparent;
  color: #e6f0ff;
  border-radius: 0.5rem;
  font-size: 0.875rem; /* Smaller text size */
  transition: background-color 0.3s, color 0.3s;
}

.modern-nav-link:hover {
  background-color: var(--main-strong);
  color: #3e44f1;
}

.modern-nav-link i {
  width: 20px;
}

/* Sidebar Toggle Arrow */
.sidebar-gradient .toggle-arrow {
  font-size: 1.5rem;  /* Adjust arrow size */
  cursor: pointer;
  transition: transform 0.3s ease-in-out;
}

.sidebar-gradient .toggle-arrow:hover {
  transform: rotate(180deg); /* Rotate arrow on hover */
}

.custom-scrollbar {
  overflow-y: auto;
}

/* -------------------------- */
/* Main Content Styling */
/* -------------------------- */
.header-shadow {
  background: var(--bg-color);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

body {
  background: var(--bg-color);
  color: #e6f0ff;
  min-height: 100vh;
}

.stat-card {
  background: var(--surface);
  padding: 1.5rem;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05), 0 0 0 1px rgba(59, 130, 246, 0.1);
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.card-icon {
  background: linear-gradient(135deg, var(--main-color) 0%, var(--main-strong) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.1));
}
</style>
