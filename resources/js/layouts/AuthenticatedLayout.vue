<script setup>
import { computed, ref } from 'vue'
import ApplicationLogo from '@/components/ApplicationLogo.vue'
import Dropdown from '@/components/Dropdown.vue'
import DropdownLink from '@/components/DropdownLink.vue'
import NavLink from '@/components/NavLink.vue'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const userRole = computed(() => page.props.auth.user.role)

const sidebarOpen = ref(false)
const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value
}

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
  return 'student.dashboard'
})
</script>

<template>
  <div class="modern-layout flex h-screen overflow-hidden">
    <div
      v-if="sidebarOpen"
      class="fixed inset-0 z-30 bg-black/60 lg:hidden"
      @click="toggleSidebar"
    ></div>

    <aside
      class="sidebar-gradient sidebar-glass fixed z-40 h-full w-64 flex-shrink-0 flex-col text-white
             transition-transform duration-300 lg:static lg:translate-x-0"
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
      <div class="logo-section flex h-16 items-center justify-center">
        <Link :href="route(logoRoute)">
          <ApplicationLogo class="block h-9 w-auto opacity-90" />
        </Link>
      </div>

      <nav class="custom-scrollbar flex-grow overflow-y-auto px-4 py-6">
        <div class="space-y-2">
          <NavLink
            v-for="link in getLinks"
            :key="link.route"
            :href="route(link.route)"
            :active="route().current(link.route)"
            class="modern-nav-link"
            @click="sidebarOpen = false"
          >
            <i :class="[link.icon, 'w-5 text-center mr-3']"></i>
            {{ link.label }}
          </NavLink>
        </div>
      </nav>
    </aside>

    <div class="flex flex-1 flex-col overflow-hidden main-bg">
      <header
        class="header-shadow sticky top-0 z-20 flex items-center justify-between px-4 sm:px-6 py-3 topbar-bg"
      >
        <div class="flex items-center gap-3">
          <button class="lg:hidden text-blue-300" @click="toggleSidebar">
            <i class="fas fa-bars text-lg"></i>
          </button>

          <span class="hidden sm:inline text-sm font-semibold text-blue-100">
            Attendance System
          </span>
        </div>

        <Dropdown align="right" width="48">
          <template #trigger>
            <button
              type="button"
              class="inline-flex items-center rounded-full border border-blue-400/20
                     bg-blue-900/40 px-3 sm:px-4 py-2 text-sm font-medium
                     text-blue-100 hover:bg-blue-900/60 transition
                     focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              {{ $page.props.auth.user.name }}
              <svg class="ms-2 h-4 w-4 text-blue-400" xmlns="http://www.w3.org/2000/svg"
                   viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clip-rule="evenodd" />
              </svg>
            </button>
          </template>

          <template #content>
            <DropdownLink :href="route('profile.edit')" class="hover:bg-blue-900/10">
              <i class="fas fa-user-circle mr-2"></i> Profile
            </DropdownLink>
            <div class="my-1 border-t border-blue-200/20"></div>
            <DropdownLink
              :href="route('logout')"
              method="post"
              as="button"
              class="text-red-400 hover:bg-red-500/10"
            >
              <i class="fas fa-sign-out-alt mr-2"></i> Log Out
            </DropdownLink>
          </template>
        </Dropdown>
      </header>

      <section class="mx-auto w-full max-w-7xl px-4 sm:px-6 pt-6">
        <div class="font-semibold text-base sm:text-lg text-blue-100">
          <slot name="header" />
        </div>
      </section>

      <main class="flex-1 overflow-y-auto">
        <div class="container mx-auto max-w-7xl px-4 sm:px-6 py-6 ">
          <slot />
        </div>
      </main>
    </div>
  </div>
</template>

<style scoped>
/* الخلفية العامة */
.main-bg {
  background: radial-gradient(circle at top, #0f172a, #020617);
}

/* Sidebar */
.sidebar-gradient {
  background: linear-gradient(180deg, #0c1a48, #030716);
}

.sidebar-glass {
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  box-shadow: inset -1px 0 0 rgba(96,165,250,0.15);
}

/* Logo */
.logo-section {
  border-bottom: 1px solid rgba(96,165,250,0.15);
}

/* Nav links */
.modern-nav-link {
  padding: 0.75rem 1rem;
  border-radius: 0.75rem;
  color: #93c5fd;
  font-weight: 500;
  display: flex;
  align-items: center;
  transition: all 0.25s ease;
}
.modern-nav-link:hover {
  color: #fff;
  background: rgba(59,130,246,0.15);
}
.modern-nav-link[data-active="true"] {
  color: #fff;
  font-weight: 700;
  background: linear-gradient(90deg, rgba(59,130,246,0.35), rgba(59,130,246,0.15));
  box-shadow: inset 0 0 15px rgba(59,130,246,0.35);
}

/* Scrollbar */
.custom-scrollbar::-webkit-scrollbar { width: 6px }
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(96,165,250,0.4);
  border-radius: 10px;
}

/* Topbar */
.topbar-bg {
  background: linear-gradient(180deg, #0c1a47, #0e1d51);
}

.header-shadow {
  box-shadow: 0 8px 25px rgba(0,0,0,0.6);
}
</style>
