<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import { ref } from 'vue'

// Props from Laravel (paginator object)
const props = defineProps({
  students: Object, // paginator: { data, links, ... }
  filters: Object,
})

const search = ref(props.filters?.q || '')  // استخدام search بدلاً من searchQuery
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
      <div class="flex items-center justify-end gap-3 mt-4">
        <!-- Update face data -->
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

      <div class="flex flex-col items-start mb-6">
        <!-- Title -->
        <h2 class="text-3xl font-semibold text-gradient tracking-wide mb-2">
          Student Management
        </h2>

        <!-- Subtitle or description below the title -->
        <p class="text-base text-gray-400 font-light">
          إدارة وعرض وتحرير وحذف سجلات الطلاب
        </p>
      </div>

      <!-- Search and Add Section for Students -->
      <div class="flex justify-between items-center mb-4">
        <!-- Search Field -->
        <div class="flex-1 mr-4">
          <input
            type="text"
            v-model="search"   -->
            placeholder="Search students..."
            @keyup.enter="searchStudents"  <!-- إضافة الحدث عند الضغط على Enter -->
            class="w-full border border-blue-200 rounded-lg py-2 px-4 bg-[#1e2a47] text-white placeholder:text-blue-200 focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-300 transition-all duration-300"
          >
        </div>
        <!-- Add Student Button -->
        <Link
          :href="route('admin.students.create')"
          class="bg-gradient-to-r from-indigo-500 to-blue-500 hover:from-indigo-600 hover:to-blue-600 text-white font-semibold py-2 px-6 rounded-lg flex items-center shadow-md transition-all duration-200"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
          Add Student
        </Link>
      </div>

      <!-- Table Section for Students -->
      <div class="bg-[#1e2a47] rounded-xl shadow-lg overflow-hidden border border-blue-100">
        <div class="overflow-x-auto">
          <table class="max-w-5xl min-w-full divide-y divide-blue-200">
            <!-- Table Header -->
            <thead class="bg-blue-600">
              <tr>
                <th class="px-6 py-3 text-left text-sm font-medium text-blue-100 uppercase tracking-wider">Photo</th>
                <th class="px-6 py-3 text-left text-sm font-medium text-blue-100 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-sm font-medium text-blue-100 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-sm font-medium text-blue-100 uppercase tracking-wider">Courses</th>
                <th class="px-6 py-3 text-right text-[11px] font-semibold uppercase tracking-wider">Actions</th>
              </tr>
            </thead>

            <!-- Table Body -->
            <tbody class="bg-[#0f1b29] divide-y divide-blue-10">
              <tr v-for="student in (props.students?.data ?? [])" :key="student.id" class="hover:bg-blue-70over:shadow-md hover:scale-105 transition-all duration-200">
                <td class="px-6 py-4 whitespace-nowrap">
                  <img
                    v-if="student.photos && student.photos.length > 0"
                    :src="getPhotoUrl(student.photos[0].photo_path)"
                    alt="Student Photo"
                    class="h-10 w-10 rounded-full object-cover"
                  >
                  <div v-else class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 text-xs text-gray-500">
                    No Photo
                  </div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-100">{{ student.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-100">{{ student.email }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-100">{{ (student.courses || []).length }}</td>

                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex space-x-2 justify-center">
                    <!-- Edit Button -->
                    <Link
                      :href="route('admin.students.edit', student.id)"
                      class="inline-flex items-center px-3 py-1.5 text-white text-sm font-medium rounded-lg transition duration-150 shadow-md modern-edit-button"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M4 17.25V20h2.75l8.086-8.086-2.75-2.75L4 17.25zM18.71 8.04a1.003 1.003 0 000-1.42l-1.33-1.33a1.003 1.003 0 00-1.42 0l-1.12 1.12 2.75 2.75 1.12-1.12z"/>
                      </svg>
                      Edit
                    </Link>

                    <!-- Delete Button -->
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

              <!-- No Students Found Message -->
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
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Background gradient container (subtle blue) */
.text-gradient {
    background-image: linear-gradient(to right, #4F46E5, #3B82F6); /* Gradient from Indigo to Blue */
    -webkit-background-clip: text; /* This property makes the gradient apply to text */
    color: transparent; /* Ensures the gradient is visible in the text */
}
/* Container for table */
.table-container {
  width: 100%; /* Takes up full width */
  max-width: 1200px; /* Optional: Limits the max width of the table */
  margin: 0 auto; /* Centers the table */
}

/* Table itself */
table {
  width: 40%; /* Takes up full width of the container */
  max-width: 100%; /* Ensures table width is within its container */
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
    font-weight: 90; /* Lighter weight for the Arabic text */
}

/* Icon gradient look */
.icon-gradient {
  color: #4f46e5; /* Indigo-600 */
}

/* Primary action button (Add Student) */
.modern-button {
  background-image: linear-gradient(to right, #4F46E5 0%, #3B82F6 100%);
  box-shadow: 0 4px 10px rgba(79, 70, 229, 0.4);
}
.modern-button:hover {
  background-image: linear-gradient(to right, #4338CA 0%, #2563EB 100%);
}

/* Update button styling */
.update-button {
  background-image: linear-gradient(to right, #10B981 0%, #059669 100%);
  box-shadow: 0 3px px rgba(16,185,129,.3);
}
.update-button:hover {
  background-image: linear-gradient(to right, #059669 0%, #047857 100%);
}

/* Right-align the "Update Face Data" button */

.justify-end {
  justify-content: flex-end;
  }
/* Table action buttons */
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

/* Table head look */
.min-w-full thead {
  background-color: #3b82f6;
  border-top-left-radius: 1rem;
  border-top-right-radius: 1rem;
}
.min-w-full thead th {
    color: #DBEAFE; /* Blue-100 */
}


</style>
