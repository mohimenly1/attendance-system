<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import { ref } from 'vue'

// Props from Laravel (paginator object)
const props = defineProps({
  students: Object, // paginator: { data, links, ... }
  filters: Object,
})

const search = ref(props.filters?.q || '')
const encodeForm = useForm({})
const deleteForm = useForm({})

// Search handler
const searchStudents = () => {
  router.get(
    route('admin.students.index'),
    { q: search.value },
    { preserveState: true, replace: true }
  )
}

// Delete student
const deleteStudent = (id) => {
  if (confirm('Are you sure you want to delete this student? This action cannot be undone.')) {
    deleteForm.delete(route('admin.students.destroy', id), {
      preserveScroll: true,
      onSuccess: () => alert('Student deleted successfully.'),
      onError: () => alert('An error occurred while deleting the student.'),
    })
  }
}

// Trigger face encoding update
const triggerEncoding = () => {
  encodeForm.post(route('admin.students.encode'), {
    preserveState: true,
    onSuccess: () => alert('Face recognition data update triggered successfully.'),
    onError: () => alert('An error occurred while updating face recognition data.'),
  })
}

const getPhotoUrl = (photoPath) => `/storage/${photoPath}`
</script>

<template>
  <Head title="Student Management" />

  <AuthenticatedLayout>
    <div class="p-6 user-management-container">
      <!-- Top actions -->
      <div class="flex items-center justify-end gap-3 mt-4">
        <button
          @click="triggerEncoding"
          :disabled="encodeForm.processing"
          class="update-button inline-flex items-center gap-2 rounded-xl px-4 py-2.5 font-semibold text-white shadow-md transition disabled:opacity-70"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 6V3L8 7l4 4V8a4 4 0 014 4 4.002 4.002 0 01-4 4 4.002 4.002 0 01-3.874-3H6.11A6.002 6.002 0 0012 20a6 6 0 000-12z"/>
          </svg>
          Update Face Data
        </button>
      </div>

      <!-- Title -->
      <div class="flex flex-col items-start mb-6">
        <h2 class="text-3xl font-semibold text-gradient tracking-wide mb-2">
          Student Management
        </h2>
      </div>

      <!-- Search + Add -->
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4">
        <div class="flex-1">
          <input
            type="text"
            v-model="search"
            placeholder="Search students..."
            @keyup.enter="searchStudents"
            class="w-full border border-blue-200 rounded-lg py-2 px-4 bg-[#1e2a47] text-white placeholder:text-blue-200 focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-300 transition-all duration-300"
          />
        </div>

        <Link
          :href="route('admin.students.create')"
          class="bg-gradient-to-r from-indigo-500 to-blue-500 hover:from-indigo-600 hover:to-blue-600 text-white font-semibold py-2 px-6 rounded-lg inline-flex items-center justify-center shadow-md transition-all duration-200"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
          Add Student
        </Link>
      </div>

      <!-- Table -->
      <div class="bg-[#1e2a47] rounded-xl shadow-lg overflow-hidden border border-blue-100">
        <div class="overflow-x-auto w-full">
          <!-- ✅ Responsive: min width for table -->
          <table class="min-w-[900px] w-full divide-y divide-blue-200">
            <thead class="bg-blue-600">
              <tr>
                <th class="px-6 py-3 text-left text-sm font-medium text-blue-100 uppercase tracking-wider">Photo</th>
                <th class="px-6 py-3 text-left text-sm font-medium text-blue-100 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-sm font-medium text-blue-100 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-sm font-medium text-blue-100 uppercase tracking-wider">Courses</th>
                <th class="px-6 py-3 text-right text-[11px] font-semibold uppercase tracking-wider text-blue-100">Actions</th>
              </tr>
            </thead>

            <tbody class="bg-[#0f1b29] divide-y divide-blue-100">
              <tr
                v-for="student in (props.students?.data ?? [])"
                :key="student.id"
                class="hover:bg-blue-700/10 hover:shadow-md hover:scale-[1.01] transition-all duration-200"
              >
                <td class="px-6 py-4 whitespace-nowrap">
                  <img
                    v-if="student.photos && student.photos.length > 0"
                    :src="getPhotoUrl(student.photos[0].photo_path)"
                    alt="Student Photo"
                    class="h-10 w-10 rounded-full object-cover"
                  />
                  <div v-else class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 text-xs text-gray-500">
                    No Photo
                  </div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-100">
                  {{ student.name }}
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-100">
                  {{ student.email }}
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-100">
                  {{ (student.courses || []).length }}
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex space-x-2 justify-center">
                    <Link
                      :href="route('admin.students.edit', student.id)"
                      class="inline-flex items-center px-3 py-1.5 text-white text-sm font-medium rounded-lg transition duration-150 shadow-md modern-edit-button"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M4 17.25V20h2.75l8.086-8.086-2.75-2.75L4 17.25zM18.71 8.04a1.003 1.003 0 000-1.42l-1.33-1.33a1.003 1.003 0 00-1.42 0l-1.12 1.12 2.75 2.75 1.12-1.12z"/>
                      </svg>
                      Edit
                    </Link>

                    <button
                      @click="deleteStudent(student.id)"
                      class="inline-flex items-center px-3 py-1.5 text-white text-sm font-medium rounded-lg transition duration-150 shadow-md modern-delete-button"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path d="M6 7h12l-1 14H7L6 7zm3-3h6l1 3H8l1-3z"/>
                      </svg>
                      Delete
                    </button>
                  </div>
                </td>
              </tr>

              <tr v-if="(props.students?.data ?? []).length === 0">
                <td colspan="5" class="px-6 py-8 text-center text-blue-400">
                  <div class="flex flex-col items-center">
                    <span class="text-4xl mb-2">🚫</span>
                    <p class="text-lg">No students found.</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- (اختياري) Pagination: إذا عندك links في paginator -->
      <div v-if="props.students?.links?.length" class="mt-6 flex flex-wrap gap-2">
        <Link
          v-for="(link, idx) in props.students.links"
          :key="idx"
          :href="link.url || '#'"
          class="px-3 py-2 rounded-lg border border-blue-200 text-sm"
          :class="[
            link.active ? 'bg-blue-600 text-white border-blue-600' : 'bg-[#1e2a47] text-blue-100',
            !link.url ? 'opacity-50 pointer-events-none' : 'hover:bg-blue-700/20'
          ]"
          v-html="link.label"
        />
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Gradient title */
.text-gradient {
  background-image: linear-gradient(to right, #4F46E5, #3B82F6);
  -webkit-background-clip: text;
  color: transparent;
}
.text-gradient:hover {
  background-image: linear-gradient(to right, #2563EB, #4338CA);
}
.text-gradient {
  font-size: 1.5rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 2px;
  text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.2);
}

/* Subtitle */
p {
  font-size: 1.125rem;
  color: #A0AEC0;
  margin-top: 0.5rem;
  font-weight: 300;
}

/* Buttons */
.modern-edit-button {
  background-image: linear-gradient(to right, #3B82F6 0%, #60A5FA 100%);
  box-shadow: 0 2px 5px rgba(59,130,246,.4);
}
.modern-edit-button:hover {
  background-image: linear-gradient(to right, #2563EB 0%, #3B82F6 100%);
}

.modern-delete-button {
  background-image: linear-gradient(to right, #EF4444 0%, #F87171 100%);
  box-shadow: 0 2px 5px rgba(239,68,68,.4);
}
.modern-delete-button:hover {
  background-image: linear-gradient(to right, #DC2626 0%, #EF4444 100%);
}

.update-button {
  background-image: linear-gradient(to right, #10B981 0%, #059669 100%);
  box-shadow: 0 3px 10px rgba(16,185,129,.3);
}
.update-button:hover {
  background-image: linear-gradient(to right, #059669 0%, #047857 100%);
}
</style>
