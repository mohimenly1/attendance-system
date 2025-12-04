<script setup>
/*
  resources/js/Pages/admin/Schedules/Index.vue
  - نفس البنية والوظائف، مع ضبط محاذاة ومسافات صندوق الفلاتر (mid box)
*/

import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import { Inertia } from '@inertiajs/inertia'
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'

// Props from Laravel controller
const props = defineProps({
  schedules: { type: Object, default: () => ({ data: [], links: [] }) },
  courses:   { type: Array,  default: () => [] },
  // قد تأتي من السيرفر بأسماء course_id/search — فندعم الحالتين
  filters:   { type: Object, default: () => ({ course: '', course_id: '', day: '', q: '', search: '' }) },
  can:       { type: Object, default: () => ({ create: true, edit: true, delete: true }) },
})

// Safe access to page props
const page = usePage()
function getPageProps() {
  try {
    if (page && page.props && typeof page.props === 'object') {
      if ('value' in page.props) return page.props.value || {}
      return page.props || {}
    }
  } catch {}
  return {}
}

const flash     = computed(() => getPageProps().flash ?? {})
const authUser  = computed(() => getPageProps().auth?.user ?? null)

// Filter form (ندعم كلا الاسمين من السيرفر)
const filterForm = useForm({
  course: props.filters.course ?? props.filters.course_id ?? '',
  day:    props.filters.day ?? '',
  q:      props.filters.q ?? props.filters.search ?? '',
})

// UI states
const confirming = ref(false)
const toDeleteId = ref(null)
// ✅ بدون Friday
const days = ['Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday']

const courseMenuOpen = ref(false)
const dayMenuOpen    = ref(false)
const courseBtnRef   = ref(null)
const dayBtnRef      = ref(null)

const schedules = computed(() => props.schedules || { data: [], links: [] })
const coursesList = computed(() => props.courses || [])
const canCreate = computed(() => props.can?.create ?? (authUser.value?.role === 'admin'))
const canEdit   = computed(() => props.can?.edit   ?? (authUser.value?.role === 'admin'))
const canDelete = computed(() => props.can?.delete ?? (authUser.value?.role === 'admin'))

// Ziggy-aware URL helper
function urlFor(name, params = null) {
  try {
    // eslint-disable-next-line no-undef
    return typeof route === 'function' ? route(name, params) : fallbackFor(name, params)
  } catch {
    return fallbackFor(name, params)
  }
}
function fallbackFor(name, params = null) {
  switch (name) {
    case 'admin.schedules.index':     return '/admin/schedules'
    case 'admin.schedules.create':    return '/admin/schedules/create'
    case 'admin.schedules.edit':      return `/admin/schedules/${typeof params === 'object' ? params.id ?? '' : params}/edit`
    case 'admin.schedules.destroy':   return `/admin/schedules/${typeof params === 'object' ? params.id ?? '' : params}`
    case 'admin.schedules.export':    return '/admin/schedules/export'
    case 'admin.schedules.exportPdf': return '/admin/schedules/export-pdf' // ✅ جديد
    default:                          return '/admin/schedules'
  }
}

// === Actions ===
// نحول مفاتيح الواجهة إلى مفاتيح الكنترولر
function buildParams() {
  const params = {
    course_id: filterForm.course || undefined,
    day:       filterForm.day || undefined,
    search:    filterForm.q || undefined,
  }
  // إزالة undefined حتى لا تُرسل في الكويري
  return Object.fromEntries(Object.entries(params).filter(([_, v]) => v !== undefined && v !== ''))
}

function applyFilters(e) {
  e?.preventDefault?.()
  const params = buildParams()
  Inertia.get(urlFor('admin.schedules.index'), params, { preserveState: true, replace: true })
}

function resetFilters() {
  filterForm.course = ''
  filterForm.day = ''
  filterForm.q = ''
  applyFilters()
}

function confirmDelete(id) { toDeleteId.value = id; confirming.value = true }
function cancelDelete()    { confirming.value = false; toDeleteId.value = null }
function deleteNow() {
  if (!toDeleteId.value) return
  Inertia.delete(urlFor('admin.schedules.destroy', toDeleteId.value), {
    onFinish: () => { confirming.value = false; toDeleteId.value = null }
  })
}
function goto(url) { if (url) Inertia.visit(url, { preserveState: true }) }

// ✅ Export CSV مع تمرير نفس الفلاتر
function exportCsv() {
  const params = new URLSearchParams(buildParams()).toString()
  const url = urlFor('admin.schedules.export') + (params ? `?${params}` : '')
  window.open(url, '_blank')
}

// ✅ Export PDF مع نفس الفلاتر
function exportPdf() {
  const params = new URLSearchParams(buildParams()).toString()
  const url = urlFor('admin.schedules.exportPdf') + (params ? `?${params}` : '')
  window.open(url, '_blank')
}

// Dropdown helpers
function toggleCourseMenu(){ courseMenuOpen.value = !courseMenuOpen.value; if (courseMenuOpen.value) dayMenuOpen.value = false }
function toggleDayMenu(){    dayMenuOpen.value    = !dayMenuOpen.value;    if (dayMenuOpen.value)    courseMenuOpen.value = false }
function selectCourse(v){ filterForm.course = v; courseMenuOpen.value = false }
function selectDay(v){    filterForm.day = v;    dayMenuOpen.value = false }

// Close on outside click
function onDocumentClick(e) {
  if (courseBtnRef.value && !courseBtnRef.value.contains(e.target)) courseMenuOpen.value = false
  if (dayBtnRef.value && !dayBtnRef.value.contains(e.target))       dayMenuOpen.value = false
}
onMounted(() => document.addEventListener('click', onDocumentClick))
onBeforeUnmount(() => document.removeEventListener('click', onDocumentClick))
</script>

<template>
  <Head title="Schedule Management" />

  <AuthenticatedLayout>
    <!-- Header -->
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-xl font-semibold text-blue-900">Schedule Management</h2>
          <p class="text-sm text-blue-700/70">Manage course schedules — create, edit, delete and filter.</p>
        </div>

        <div class="flex items-center gap-3">
          <Link
            v-if="canCreate"
            :href="urlFor('admin.schedules.create')"
            class="modern-button inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold text-white shadow-lg"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
            </svg>
            Create Schedule
          </Link>

          <button
            @click="exportCsv"
            class="neutral-button inline-flex items-center gap-2 rounded-xl border px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 3a1 1 0 011 1v9.586l2.293-2.293a1 1 0 111.414 1.414l-4.007 4.007a1 1 0 01-1.414 0L7.279 12.707a1 1 0 111.414-1.414L11 12.586V4a1 1 0 011-1z"/>
              <path d="M5 20a1 1 0 011-1h12a1 1 0 110 2H6a1 1 0 01-1-1z"/>
            </svg>
            Export CSV
          </button>

          <!-- ✅ زر Export PDF الجديد -->
          <button
            @click="exportPdf"
            class="neutral-button inline-flex items-center gap-2 rounded-xl border px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
              <path d="M6 2h9l5 5v15a1 1 0 01-1 1H6a1 1 0 01-1-1V3a1 1 0 011-1z"/>
            </svg>
            Export PDF
          </button>
        </div>
      </div>
    </template>

    <!-- Top Apply box (هادئ) -->
    <div class="mb-4 rounded-2xl border border-blue-100 bg-gradient-to-r from-[#e8efff] via-[#dee8ff] to-[#d5e2ff] p-4 shadow">
      <form @submit.prevent="applyFilters" class="flex items-center gap-2">
        <button type="submit" class="w-full rounded-lg px-4 py-2 font-semibold text-white modern-button">
          Apply Filters
        </button>
      </form>
    </div>

    <!-- ===== Mid Filters Box (محاذاة ومسافات متساوية) ===== -->
    <div class="rounded-2xl border border-blue-200 bg-gradient-to-r from-[#f0f6ff] via-[#ebf2ff] to-[#e4edff] shadow text-blue-900 px-4 sm:px-6 py-4">
      <form
        @submit.prevent="applyFilters"
        class="mx-auto w-full max-w-6xl grid grid-cols-12 items-end gap-x-4 gap-y-3"
      >
        <!-- Course -->
        <div class="col-span-12 md:col-span-3">
          <div class="relative" ref="courseBtnRef">
            <button
              type="button"
              @click="toggleCourseMenu"
              class="filter-control w-full rounded-lg bg-white px-3 py-2 ring-1 ring-blue-100 shadow-sm h-9"
            >
              <span class="text-sm text-gray-600">Course</span>
              <span class="ml-2 text-sm text-gray-700" v-if="filterForm.course">
                {{ (coursesList.find(c => String(c.id) === String(filterForm.course))?.name) ?? 'Selected' }}
              </span>
              <span class="ml-2 text-sm text-gray-500" v-else>All</span>
              <svg class="ml-2 h-4 w-4 text-blue-600" viewBox="0 0 24 24" fill="none">
                <path d="M7 10l5 5 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </button>

            <ul
              v-if="courseMenuOpen"
              class="filter-dropdown absolute z-40 mt-2 max-h-56 w-60 overflow-auto rounded-xl border border-slate-200 bg-white p-2 shadow-lg"
            >
              <li @click="selectCourse('')" class="cursor-pointer rounded-lg px-3 py-2 hover:bg-slate-100">
                <span class="text-sm">All</span>
              </li>
              <li
                v-for="c in coursesList" :key="c.id"
                class="cursor-pointer rounded-lg px-3 py-2 hover:bg-slate-100"
                @click="selectCourse(c.id)"
              >
                <span class="truncate text-sm">{{ c.name }}</span>
              </li>
            </ul>
          </div>
        </div>

        <!-- Day -->
        <div class="col-span-12 md:col-span-2">
          <div class="relative" ref="dayBtnRef">
            <button
              type="button"
              @click="toggleDayMenu"
              class="filter-control w-full rounded-lg bg-white px-3 py-2 ring-1 ring-blue-100 shadow-sm h-9"
            >
              <span class="text-sm text-gray-600">Day</span>
              <span class="ml-2 text-sm text-gray-700" v-if="filterForm.day">{{ filterForm.day }}</span>
              <span class="ml-2 text-sm text-gray-500" v-else>All</span>
              <svg class="ml-2 h-4 w-4 text-blue-600" viewBox="0 0 24 24" fill="none">
                <path d="M7 10l5 5 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </button>

            <ul
              v-if="dayMenuOpen"
              class="filter-dropdown absolute z-40 mt-2 max-h-56 w-44 overflow-auto rounded-xl border border-slate-200 bg-white p-2 shadow-lg"
            >
              <li @click="selectDay('')" class="cursor-pointer rounded-lg px-3 py-2 hover:bg-slate-100">
                <span class="text-sm">All</span>
              </li>
              <li
                v-for="d in days" :key="d"
                class="cursor-pointer rounded-lg px-3 py-2 hover:bg-slate-100"
                @click="selectDay(d)"
              >
                <span class="text-sm">{{ d }}</span>
              </li>
            </ul>
          </div>
        </div>

        <!-- Search -->
        <div class="col-span-12 md:col-span-5">
          <div class="relative">
            <input
              v-model="filterForm.q"
              type="text"
              placeholder="Course name"
              class="w-full rounded-lg border border-blue-100 bg-white px-3 py-2 text-sm shadow-sm focus:ring-2 focus:ring-blue-200 h-9"
            />
            <svg class="absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-blue-600/80" viewBox="0 0 24 24" fill="none">
              <path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
              <circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="1.6" fill="none"/>
            </svg>
          </div>
        </div>

        <!-- Buttons -->
        <div class="col-span-12 md:col-span-2 flex gap-2 md:justify-end">
          <button
            type="submit"
            class="inline-flex items-center justify-center gap-1.5 rounded-lg px-4 py-2 text-sm font-semibold text-white shadow-md
                   bg-gradient-to-r from-green-400 via-emerald-500 to-green-600 hover:from-green-500 hover:to-emerald-600 h-9 w-full md:w-auto"
          >
            Apply
          </button>

          <button
            type="button"
            @click="resetFilters"
            class="inline-flex items-center justify-center gap-1.5 rounded-lg px-3 py-2 text-sm font-semibold text-white shadow-md
                   bg-gradient-to-r from-rose-400 via-red-500 to-pink-500 hover:from-rose-500 hover:to-red-600 h-9 w-full md:w-auto"
          >
            Reset
          </button>
        </div>
      </form>
    </div>
    <!-- ===== /Mid Filters Box ===== -->

    <!-- Table -->
    <div class="overflow-x-auto rounded-2xl border border-blue-100 bg-white shadow mt-6">
      <table class="min-w-full divide-y divide-blue-100">
        <thead class="bg-blue-600 text-blue-100">
          <tr>
            <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider">Course</th>
            <!-- ✅ عمود المعلّم -->
            <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider">Teacher</th>
            <!-- ✅ عمود القاعة -->
            <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider">Classroom</th>
            <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider">Day</th>
            <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider">Start</th>
            <th class="px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider">End</th>
            <th class="px-6 py-3 text-right text-[11px] font-semibold uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-blue-50 bg-white">
          <tr v-for="s in schedules.data" :key="s.id" class="hover:bg-[#f6fbff]">
            <td class="px-6 py-4 font-medium text-blue-900">{{ s.course?.name ?? '-' }}</td>
            <!-- ✅ عرض اسم المعلّم -->
            <td class="px-6 py-4 text-blue-700">{{ s.teacher?.name ?? '-' }}</td>
            <!-- ✅ عرض اسم القاعة -->
            <td class="px-6 py-4 text-blue-700">{{ s.classroom?.name ?? '-' }}</td>
            <td class="px-6 py-4 text-blue-700">{{ s.day_of_week }}</td>
            <td class="px-6 py-4 text-blue-700">{{ s.start_time }}</td>
            <td class="px-6 py-4 text-blue-700">{{ s.end_time }}</td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-2">
                <Link
                  v-if="canEdit"
                  :href="urlFor('admin.schedules.edit', s.id)"
                  class="modern-edit-button inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-medium text-white shadow-md"
                >
                  Edit
                </Link>
                <button
                  v-if="canDelete"
                  @click="confirmDelete(s.id)"
                  class="modern-delete-button inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-medium text-white shadow-md"
                >
                  Delete
                </button>
              </div>
            </td>
          </tr>

          <tr v-if="!schedules.data || schedules.data.length === 0">
            <!-- ✅ تعديل colspan ليطابق عدد الأعمدة الجديد (7 أعمدة بدون Note) -->
            <td colspan="7" class="px-6 py-8 text-center text-blue-500">No schedules found.</td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="flex justify-end p-4">
        <nav v-if="schedules.links" class="flex items-center gap-2">
          <template v-for="link in schedules.links" :key="link.label">
            <button
              v-if="link.url"
              v-html="link.label"
              @click.prevent="goto(link.url)"
              class="rounded-lg border px-3 py-1 text-sm transition"
              :class="{ 'bg-blue-600 text-white border-blue-600': link.active, 'bg-white border-blue-200': !link.active }"
            ></button>
            <span v-else class="px-2 text-sm text-blue-300" v-html="link.label"></span>
          </template>
        </nav>
      </div>
    </div>

    <!-- Confirm modal -->
    <div v-if="confirming" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
      <div class="w-full max-w-sm rounded-2xl border border-blue-100 bg-white p-6 shadow-xl">
        <h3 class="mb-2 font-semibold text-blue-900">Confirm delete</h3>
        <p class="mb-4 text-sm text-blue-700/80">Are you sure you want to delete this schedule?</p>
        <div class="flex justify-end gap-2">
          <button @click="cancelDelete" class="rounded-lg px-4 py-2 neutral-button">Cancel</button>
          <button @click="deleteNow" class="rounded-lg px-4 py-2 text-white modern-delete-button">Delete</button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Table basics */
table { border-collapse: collapse; width: 100%; }
th { font-weight: 600; text-align: left; }
td { vertical-align: middle; }

/* Filter controls */
.filter-control { display:inline-flex; align-items:center; gap:.5rem; }
.filter-dropdown { box-shadow: 0 10px 24px rgba(2,6,23,.10); backdrop-filter: blur(2px); }

/* Buttons theme */
.modern-button {
  background-image: linear-gradient(to right, #4F46E5 0%, #3B82F6 100%);
  box-shadow: 0 4px 10px rgba(79,70,229,.35);
  color:#fff;
}
.neutral-button {
  border:1px solid #e5e7eb; background:#fff; color:#374151;
}
.modern-edit-button {
  background-image: linear-gradient(to right, #3B82F6 0%, #60A5FA 100%);
  box-shadow: 0 2px 6px rgba(59,130,246,.35); color:#fff;
}
.modern-delete-button {
  background-image: linear-gradient(to right, #EF4444 0%, #F87171 100%);
  box-shadow: 0 2px 6px rgba(239,68,68,.35); color:#fff;
}
</style>
