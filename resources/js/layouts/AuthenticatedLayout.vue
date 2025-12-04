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
  { route: 'teacher.dashboard', label: 'Courses', icon: 'fas fa-chalkboard' },
  { route: 'teacher.courses.create', label: 'Add New Course', icon: 'fas fa-folder-plus' },
  { route: 'teacher.students.create', label: 'Add New Student', icon: 'fas fa-user-plus' },
]

// ✅ Student pages in English
const studentLinks = [
  { route: 'student.dashboard', label: 'Dashboard', icon: 'fas fa-home' },
]

const getLinks = computed(() => {
  if (userRole.value === 'admin') return adminLinks
  if (userRole.value === 'teacher') return teacherLinks
  if (userRole.value === 'student') return studentLinks
  return []
})

// ✅ Logo route adapts by role
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
          <NavLink
            v-for="link in getLinks"
            :key="link.route"
            :href="route(link.route)"
            :active="route().current(link.route)"
            class="modern-nav-link"
          >
            <i :class="[link.icon, 'w-5 text-center mr-3']"></i>
            {{ link.label }}
          </NavLink>
        </div>
      </nav>
    </aside>

    <!-- Main -->
    <div class="flex flex-1 flex-col overflow-hidden bg-gray-50">
      <!-- TOP NAVBAR (شريط علوي فقط) -->
      <header class="header-shadow sticky top-0 z-20 flex items-center justify-between bg-white px-6 py-3">
        <!-- يسار: الشعار الصغير أو عنوان التطبيق (اختياري) -->
        <div class="flex items-center gap-2">
          <span class="hidden sm:inline text-sm font-semibold text-gray-700">Attendance System</span>
        </div>

        <!-- يمين: قائمة المستخدم -->
        <div class="relative">
          <Dropdown align="right" width="48">
            <template #trigger>
              <button
                type="button"
                class="inline-flex items-center rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-medium leading-4 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                {{ $page.props.auth.user.name }}
                <svg class="ms-2 -me-0.5 h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                  fill="currentColor">
                  <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
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

      <!-- PAGE HEADER (الهيدر الحقيقي للصفحة: عنوان + أزرار كبيرة) -->
      <section class="mx-auto w-full max-w-7xl px-6 pt-6">
        <div class="font-semibold text-lg text-gray-800">
          <!-- الهيدر القادم من الصفحة (Student Management ...) -->
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
/* Sidebar Base Styling */
/* -------------------------- */
.sidebar-gradient {
  background: linear-gradient(180deg, #1d4ed8 0%, #3b82f6 100%);
  position: relative;
  border-right: 1px solid rgba(255, 255, 255, 0.1);
}

/* Glassmorphism */
.sidebar-glass {
  background: rgba(17, 1, 97, 0.9);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
}

.logo-section {
  box-shadow: 0 1px 0 rgba(61, 147, 187, 0.1) inset;
}

/* Nav link */
.modern-nav-link {
  width: 100%;
  padding: 0.75rem 1rem;
  margin-bottom: 0.5rem;
  border-radius: 0.75rem;
  color: #bfdbfe;
  font-weight: 500;
  display: flex;
  align-items: center;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
  z-index: 1;
}
.modern-nav-link:hover {
  color: #ffffff;
  background-color: rgba(13, 101, 160, 0.15);
  transform: translateX(5px);
}
.modern-nav-link[data-active="true"] {
  color: #ffffff;
  font-weight: 700;
  background: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%);
  box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
  transform: translateX(0);
}
.modern-nav-link[data-active="true"]::before {
  content: '';
  position: absolute;
  top: 0;
  right: 0;
  height: 100%;
  width: 4px;
  background-color: #ffffff;
  border-radius: 0 4px 4px 0;
}

/* Scrollbar */
.custom-scrollbar::-webkit-scrollbar { width: 6px }
.custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); border-radius: 10px }
.custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.3); border-radius: 10px }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.5) }

/* Header shadow */
.header-shadow { box-shadow: 0 2px 4px rgba(0,0,0,0.05) }
</style>
