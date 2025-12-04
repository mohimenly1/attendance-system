<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
  student: Object, // { name, avatar }
  courses: Array,
});
</script>

<template>
  <Head title="Student Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl leading-tight text-slate-900">
        🎓 Student Dashboard
      </h2>
    </template>

    <!-- خلفية تقنية متدرّجة + ضوء خلفي -->
    <div
      class="min-h-[calc(100vh-8rem)] relative overflow-hidden
             bg-gradient-to-b from-slate-50 via-sky-100/70 to-blue-200/60
             backdrop-blur-sm"
    >
      <!-- طبقة توهج خفيف (glow) -->
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-12 left-1/4 w-80 h-80 bg-sky-200/40 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/5 w-96 h-96 bg-blue-300/30 rounded-full blur-3xl"></div>
      </div>

      <div class="relative mx-auto max-w-7xl space-y-8 p-4 sm:p-8">
        <!-- 👤 بطاقة البروفايل (مُحسّنة بتدرّج وظلال وتوهج داخلي) -->
        <section
          class="relative overflow-hidden rounded-2xl shadow-lg ring-1 ring-slate-200/70
                 bg-gradient-to-br from-sky-100 via-blue-100 to-sky-200
                 p-5 transition-all duration-300 hover:shadow-xl"
        >
          <!-- توهج داخلي ناعم -->
          <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-0 left-1/3 w-40 h-40 bg-sky-300/30 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 right-1/4 w-56 h-56 bg-blue-400/20 rounded-full blur-2xl"></div>
          </div>

          <!-- المحتوى -->
          <div class="relative flex items-center gap-4 z-10">
            <img
              :src="student?.avatar"
              :alt="student?.name"
              class="h-16 w-16 rounded-full ring-2 ring-white shadow-md object-cover"
            />
            <div class="min-w-0">
              <p class="text-xs uppercase tracking-wide text-slate-600">Welcome back</p>
              <p class="text-lg font-semibold text-slate-900 truncate">
                {{ student?.name }}
              </p>
            </div>
          </div>
        </section>

        <!-- 📚 المواد -->
        <section>
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
              <span>📚</span>
              <span>My Courses</span>
            </h3>
          </div>

          <div
            v-if="courses.length"
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
          >
            <Link
              v-for="course in courses"
              :key="course.id"
              :href="route('student.courses.show', course.id)"
              class="group block rounded-2xl bg-white/90 backdrop-blur
                     ring-1 ring-slate-200 shadow-lg
                     hover:shadow-xl hover:ring-sky-300 transition-all duration-300 hover:-translate-y-0.5"
            >
              <!-- شريط علوي تزييني بتدرّج -->
              <div class="h-1.5 w-full rounded-t-2xl bg-gradient-to-r from-sky-400 via-blue-500 to-indigo-500 opacity-80 group-hover:opacity-100 transition-opacity"></div>

              <div class="p-5">
                <div class="flex items-start justify-between">
                  <h4
                    class="text-lg font-semibold text-slate-900 group-hover:text-sky-700 transition-colors"
                  >
                    {{ course.name }}
                  </h4>

                  <span
                    class="inline-flex items-center rounded-full bg-sky-50 px-2.5 py-0.5 text-xs font-semibold text-sky-700 ring-1 ring-inset ring-sky-200"
                  >
                    {{ course.code }}
                  </span>
                </div>

                <p class="mt-2 text-sm text-slate-600">
                  <span class="text-slate-500">Taught by:</span>
                  <span class="font-medium text-slate-900"> {{ course.teacher.name }} </span>
                </p>

                <!-- بار حيوي عند الهوفر -->
                <div
                  class="mt-4 h-10 w-full rounded-lg bg-gradient-to-r from-sky-50 to-blue-100/70 opacity-0 group-hover:opacity-100 transition-opacity"
                ></div>
              </div>
            </Link>
          </div>

          <div
            v-else
            class="rounded-2xl bg-white p-6 text-center text-slate-500 ring-1 ring-slate-200 shadow-sm"
          >
            You are not enrolled in any courses yet.
          </div>
        </section>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
