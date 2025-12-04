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
      <!-- تعديل تنسيق العنوان -->
      <h2 class="text-2xl font-semibold text-gradient tracking-wide mb-4">
        Student Dashboard
      </h2>
    </template>

    <div
      class="student-dashboard-root min-h-[calc(100vh-8rem)] relative overflow-hidden"
      :style="{ background: '#1a2b42' /* لون موحد */ }"
    >
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-12 left-1/4 w-80 h-80 rounded-full blur-3xl glow-left"></div>
        <div class="absolute bottom-0 right-1/5 w-96 h-96 rounded-full blur-3xl glow-right"></div>
      </div>

      <!-- 💎 المحتوى الداخلي -->
      <div class="relative mx-auto max-w-7xl space-y-8 p-4 sm:p-8">
        <!-- 👤 بطاقة البروفايل -->
        <section
          class="profile-card relative overflow-hidden rounded-2xl shadow-lg ring-1 p-5 transition-all duration-300 hover:shadow-xl"
        >
          <div class="absolute inset-0 pointer-events-none profile-card-glows"></div>

          <div class="relative flex items-center gap-4 z-10">
            <img
              :src="student?.avatar"
              :alt="student?.name"
              class="h-16 w-16 rounded-full ring-2 ring-white shadow-md object-cover"
            />
            <div class="min-w-0">
              <!-- إضافة تنسيق النص بنفس تنسيق العنوان -->
              <p class="text-xs uppercase tracking-wide profile-muted">Welcome </p>
              <p class="text-xs uppercase tracking-wide profile-muted">Good Luck</p>


              <p class="text-base font-semibold text-gradient truncate">
                {{ student?.name }}
              </p>
            </div>
          </div>
        </section>

        <!-- 📚 المواد -->
        <section>
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold section-title flex items-center gap-2">
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
              class="course-card group block rounded-2xl bg-white/90 backdrop-blur ring-1 shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5"
            >
              <!-- شريط علوي تزييني بتدرّج -->
              <div class="card-top-strip h-1.5 w-full rounded-t-2xl opacity-80 group-hover:opacity-100 transition-opacity"></div>

              <div class="p-5">
                <div class="flex items-start justify-between">
                  <h4 class="text-lg font-semibold course-title group-hover:text-accent transition-colors">
                    {{ course.name }}
                  </h4>

                  <span class="course-badge inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset">
                    {{ course.code }}
                  </span>
                </div>

                <p class="mt-2 text-sm course-muted">
                  <span class="text-muted-label">Taught by:</span>
                  <span class="font-medium course-instructor"> {{ course.teacher.name }} </span>
                </p>

                <!-- بار حيوي عند الهوفر -->
                <div class="mt-4 h-10 w-full rounded-lg hover-bar opacity-0 group-hover:opacity-100 transition-opacity"></div>
              </div>
            </Link>
          </div>

          <div
            v-else
            class="rounded-2xl p-6 text-center ring-1 shadow-sm empty-note"
          >
            You are not enrolled in any courses yet.
          </div>
        </section>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.text-gradient {
  background-image: linear-gradient(to right, #4F46E5, #3B82F6); /* Gradient from Indigo to Blue */
  -webkit-background-clip: text; /* This property makes the gradient apply to text */
  color: transparent; /* Ensures the gradient is visible in the text */
}

.text-gradient:hover {
  background-image: linear-gradient(to right, #2563EB, #4338CA); /* Darker gradient on hover */
}

.text-gradient {
  font-size: 1.3rem; /* Smaller font size for the title */
  font-weight: 700; /* Bold text */
  text-transform: uppercase; /* Uppercase letters for a bold impact */
  letter-spacing: 2px; /* Adjusted letter spacing */
  text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.2); /* Shadow effect to add depth */
}

/* General Dashboard Styles */
.student-dashboard-root {
  color: var(--text-color);
}

/* Glows for background decoration */
.student-dashboard-root .glow-left {
  background: radial-gradient(circle at 30% 20%, var(--ring-1), transparent 40%);
  opacity: 0.45;
  filter: blur(36px);
}
.student-dashboard-root .glow-right {
  background: radial-gradient(circle at 70% 70%, var(--ring-2), transparent 40%);
  opacity: 0.35;
  filter: blur(42px);
}

/* Profile Card Styles */
.student-dashboard-root .profile-card {
  background: linear-gradient(135deg,
    color-mix(in srgb, var(--main-color) 14%, var(--surface)),
    color-mix(in srgb, var(--main-strong) 6%, var(--surface))
  );
  border: 1px solid color-mix(in srgb, var(--ring-1) 10%, transparent);
  box-shadow: 0 18px 40px color-mix(in srgb, var(--ring-glow) 10%, rgba(0,0,0,0.16));
  padding: 1.5rem;
  transition: all 0.3s ease;
}

.student-dashboard-root .profile-card:hover {
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
  transform: translateY(-5px);
}

.student-dashboard-root .profile-card-glows {
  background:
    radial-gradient(circle, color-mix(in srgb, var(--accent) 22%, transparent), transparent 40%),
    radial-gradient(circle, color-mix(in srgb, var(--main-color) 18%, transparent), transparent 40%);
  opacity: 0.12;
}

/* Course Card Styles */
.student-dashboard-root .course-card {
  background: linear-gradient(180deg,
    color-mix(in srgb, var(--surface) 98%, transparent),
    color-mix(in srgb, var(--surface) 96%, transparent)
  );
  border: 1px solid color-mix(in srgb, var(--ring-2) 8%, transparent);
  box-shadow: 0 10px 30px rgba(2,6,23,0.06);
  color: color-mix(in srgb, var(--text-color) 95%, #000);
  padding: 1.25rem;
  border-radius: 0.75rem;
  transition: all 0.3s ease;
}

.student-dashboard-root .course-card:hover {
  box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
  transform: translateY(-3px);
}

.student-dashboard-root .course-card .card-top-strip {
  background-image: linear-gradient(90deg, var(--main-color), var(--main-strong), var(--accent));
  height: 6px;
  border-top-left-radius: 0.75rem;
  border-top-right-radius: 0.75rem;
}

/* Course Badge Styles */
.student-dashboard-root .course-badge {
  background-color: color-mix(in srgb, var(--main-color) 8%, transparent);
  color: var(--main-strong);
  border-color: color-mix(in srgb, var(--ring-2) 10%, transparent);
  padding: 0.5rem;
  border-radius: 1.25rem;
  font-size: 0.875rem;
}

/* Hover Bar Effects */
.student-dashboard-root .hover-bar {
  background: linear-gradient(90deg, color-mix(in srgb, var(--main-color) 12%, transparent), color-mix(in srgb, var(--accent) 6%, transparent));
  border-radius: 0.75rem;
  height: 6px;
  width: 100%;
}

/* Empty State Styles */
.student-dashboard-root .empty-note {
  background: linear-gradient(180deg, color-mix(in srgb, var(--surface) 98%, transparent), color-mix(in srgb, var(--surface) 96%, transparent));
  color: color-mix(in srgb, var(--muted) 85%, var(--text-color));
  border: 1px solid color-mix(in srgb, var(--ring-2) 6%, transparent);
  padding: 2rem;
  text-align: center;
  border-radius: 1rem;
}

/* Small Screens - Card Tweaks */
@media (max-width: 640px) {
  .student-dashboard-root .course-card {
    padding: 1rem;
  }
  .student-dashboard-root .profile-card {
    padding: 1.25rem;
  }
  .student-dashboard-root .card-top-strip { height: 5px; }
}
</style>
