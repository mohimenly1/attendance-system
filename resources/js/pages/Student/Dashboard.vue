الاء, [12/29/2025 7:32 PM]
<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

defineProps({
  student: Object,
  courses: Array,
})
</script>

<template>
<Head title="Student Dashboard" />

<AuthenticatedLayout>
  <div class="dashboard-container min-h-screen p-4 sm:p-6 space-y-6 sm:space-y-8">

    <!-- ===================== Page Title ===================== -->
    <h1 class="dashboard-title text-gradient">
      Student Dashboard
    </h1>

    <!-- ===================== Profile Card ===================== -->
    <section class="stat-card profile-card">
      <img
        :src="student?.avatar"
        :alt="student?.name"
        class="profile-avatar"
      />
      <div class="profile-text">
        <p class="profile-subtitle">Welcome back</p>
        <p class="stat-number">
          {{ student?.name }}
        </p>
      </div>
    </section>

    <!-- ===================== Courses ===================== -->
    <section>
      <h2 class="section-title mb-4">My Courses</h2>

      <div
        v-if="courses.length"
        class="courses-grid"
      >
        <Link
          v-for="course in courses"
          :key="course.id"
          :href="route('student.courses.show', course.id)"
          class="stat-card course-card group"
        >
          <div class="course-header">
            <h3 class="course-title">
              {{ course.name }}
            </h3>

            <span class="time-badge">
              {{ course.code }}
            </span>
          </div>

          <p class="course-teacher">
            Taught by
            <span class="course-teacher-name">
              {{ course.teacher.name }}
            </span>
          </p>
        </Link>
      </div>

      <div
        v-else
        class="list-card text-center text-blue-200/70"
      >
        No enrolled courses.
      </div>
    </section>

  </div>
</AuthenticatedLayout>
</template>

<style scoped>
/* ===================== Background ===================== */
.dashboard-container{
  background: radial-gradient(circle at top, #0f172a, #020617);
}

/* ===================== Title ===================== */
.text-gradient{
  background: linear-gradient(to right, #4F46E5, #3B82F6);
  -webkit-background-clip: text;
  color: transparent;
}

.dashboard-title{
  font-size: clamp(1.4rem, 4vw, 1.875rem);
  font-weight: 800;
  letter-spacing: 1.2px;
  line-height: 1.2;
}

/* ===================== Cards ===================== */
.stat-card,
.list-card{
  background: linear-gradient(180deg, #020617, #020617);
  border-radius: 16px;
  padding: 1.1rem;
  position: relative;
  overflow: hidden;
  box-shadow:
    inset 0 0 30px rgba(59,130,246,0.25),
    0 15px 40px rgba(0,0,0,0.7);
}

/* ===================== Profile ===================== */
.profile-card{
  display: flex;
  align-items: center;
  gap: 1rem;
  flex-wrap: wrap;
}

.profile-avatar{
  width: 56px;
  height: 56px;
  border-radius: 999px;
  object-fit: cover;
  ring: 2px solid #60a5fa;
}

.profile-text{
  min-width: 0;
}

.profile-subtitle{
  font-size: .85rem;
  color: rgba(147,197,253,.7);
}

.stat-number{
  font-size: clamp(1.1rem, 3vw, 1.4rem);
  font-weight: 700;
  color: #e5f0ff;
  word-break: break-word;
}

/* ===================== Courses Grid ===================== */
.courses-grid{
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
  gap: 1.25rem;
}

/* ===================== Course Card ===================== */
.course-card{
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  min-height: 150px;
  transition: transform .25s ease;
}

.course-card:hover{
  transform: translateY(-4px);
}

.course-header{
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: .75rem;
}

.course-title{
  font-size: 1rem;
  font-weight: 600;
  color: #bfdbfe;
  line-height: 1.3;
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}

.course-teacher{
  margin-top: .5rem;
  font-size: .85rem;
  color: rgba(147,197,253,.7);
}

.course-teacher-name{
  color: #e5f0ff;
  font-weight: 500;
}

/* ===================== Badges ===================== */
.time-badge{
  background: rgba(59,130,246,0.2);
  color: #93c5fd;
  padding: 0.25rem 0.6rem;
  border-radius: 999px;
  font-size: 0.7rem;
  font-weight: 600;
  white-space: nowrap;
}

/* ===================== Section ===================== */
.section-title{
  color: #bfdbfe;
  font-size: 1.05rem;
  font-weight: 600;
}

/* ===================== Glow ===================== */
.stat-card::before,
.list-card::before{
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 18px;
  background: linear-gradient(
    to bottom,
    rgba(96,165,250,0.55),
    rgba(96,165,250,0.25),
    rgba(96,165,250,0.08),
    transparent
  );
  filter: blur(12px);
}

.stat-card::after,
.list-card::after{
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 2px;
  background: linear-gradient(to right, transparent, #60a5fa, transparent);
}

/* ===================== Responsive Fine-Tuning ===================== */
@media (max-width: 640px){
  .profile-card{
    gap: .75rem;
  }

  .profile-avatar{
    width: 48px;
    height: 48px;
  }

  .course-card{
    min-height: 140px;
  }
}
</style>
