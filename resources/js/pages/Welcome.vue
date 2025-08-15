<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
      <!-- Navigation -->
      <header class="absolute inset-x-0 top-0 z-50">
        <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
          <div class="flex lg:flex-1">
            <a href="#" class="-m-1.5 p-1.5">
              <span class="sr-only">FaceAttendance</span>
              <div class="flex items-center">
                <i class="fas fa-user-check text-2xl text-indigo-600"></i>
                <span class="ml-2 text-2xl font-bold text-gray-900">FaceAttendance</span>
              </div>
            </a>
          </div>
          
          <div class="flex flex-1 justify-end">
            <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end">
              <Link
                v-if="$page.props.auth.user"
                :href="route('admin.dashboard')"
                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
              >
                Dashboard
              </Link>
  
              <template v-else>
                <Link
                  :href="route('login')"
                  class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                  Log in
                </Link>
  
                <Link
                  v-if="canRegister"
                  :href="route('register')"
                  class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                >
                  Register
                </Link>
              </template>
            </nav>
          </div>
        </nav>
      </header>
  
      <!-- Hero Section -->
      <div class="relative isolate px-6 pt-14 lg:px-8">
        <div class="mx-auto max-w-7xl pt-24 sm:pt-32 pb-24">
          <div class="text-center">
            <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">
              Revolutionize Student Attendance with AI
            </h1>
            <p class="mt-6 text-lg leading-8 text-gray-600">
              Our face recognition system provides accurate, contactless attendance tracking for educational institutions. Save time, reduce fraud, and get real-time insights.
            </p>
            <div class="mt-10 flex items-center justify-center gap-x-6">
              <Link
                :href="route('register')"
                class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
              >
                Get started
              </Link>
              <a href="#features" class="text-sm font-semibold leading-6 text-gray-900">
                Learn more <i class="fas fa-arrow-right ml-1"></i>
              </a>
            </div>
          </div>
          <div class="mt-16 flow-root sm:mt-24">
            <div class="-m-2 rounded-xl bg-gray-900/5 p-2 ring-1 ring-inset ring-gray-900/10 lg:-m-4 lg:rounded-2xl lg:p-4">
              <img
                src="/images/dashboard-preview.jpeg"
                alt="App screenshot"
                width="2432"
                height="1442"
                class="rounded-md shadow-2xl ring-1 ring-gray-900/10"
              />
            </div>
          </div>
        </div>
      </div>
  
      <!-- Features Section -->
      <div id="features" class="bg-white py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
          <div class="mx-auto max-w-2xl lg:text-center">
            <h2 class="text-base font-semibold leading-7 text-indigo-600">
              <i class="fas fa-bolt mr-2"></i>Efficient Attendance
            </h2>
            <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
              Advanced face recognition technology
            </p>
            <p class="mt-6 text-lg leading-8 text-gray-600">
              Our system uses cutting-edge AI to simplify attendance management while maintaining high accuracy and security.
            </p>
          </div>
          <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-4xl">
            <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-10 lg:max-w-none lg:grid-cols-2 lg:gap-y-16">
              <div v-for="feature in features" :key="feature.name" class="relative pl-16">
                <dt class="text-base font-semibold leading-7 text-gray-900">
                  <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600">
                    <i :class="feature.icon" class="text-white text-lg"></i>
                  </div>
                  {{ feature.name }}
                </dt>
                <dd class="mt-2 text-base leading-7 text-gray-600">{{ feature.description }}</dd>
              </div>
            </dl>
          </div>
        </div>
      </div>
  
      <!-- CTA Section -->
      <div class="bg-indigo-700">
        <div class="mx-auto max-w-7xl py-24 sm:px-6 sm:py-32 lg:px-8">
          <div class="relative isolate overflow-hidden bg-gray-900 px-6 pt-16 shadow-2xl sm:rounded-3xl sm:px-16 md:pt-24 lg:flex lg:gap-x-20 lg:px-24 lg:pt-0">
            <div class="mx-auto max-w-md text-center lg:mx-0 lg:flex-auto lg:py-32 lg:text-left">
              <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                Ready to transform your attendance system?
              </h2>
              <p class="mt-6 text-lg leading-8 text-gray-300">
                Join hundreds of educational institutions using our face recognition technology to streamline their attendance processes.
              </p>
              <div class="mt-10 flex items-center justify-center gap-x-6 lg:justify-start">
                <Link
                  :href="route('register')"
                  class="rounded-md bg-white px-3.5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                >
                  Get started
                </Link>
                <Link
                  :href="route('login')"
                  class="text-sm font-semibold leading-6 text-white"
                >
                  Already have an account? <i class="fas fa-arrow-right ml-1"></i>
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Footer -->
      <footer class="bg-white">
        <div class="mx-auto max-w-7xl overflow-hidden px-6 py-20 sm:py-24 lg:px-8">
          <nav class="-mb-6 columns-2 sm:flex sm:justify-center sm:space-x-12" aria-label="Footer">
            <div class="pb-6">
              <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">
                <i class="fas fa-info-circle mr-1"></i>About
              </a>
            </div>
            <div class="pb-6">
              <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">
                <i class="fas fa-star mr-1"></i>Features
              </a>
            </div>
            <div class="pb-6">
              <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">
                <i class="fas fa-tag mr-1"></i>Pricing
              </a>
            </div>
            <div class="pb-6">
              <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">
                <i class="fas fa-envelope mr-1"></i>Contact
              </a>
            </div>
          </nav>
          <p class="mt-10 text-center text-xs leading-5 text-gray-500">
            <i class="far fa-copyright"></i> 2023 FaceAttendance. All rights reserved.
          </p>
        </div>
      </footer>
    </div>
  </template>
  
  <script setup>
  import { Link } from '@inertiajs/vue3'
  
  defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
  })
  
  const features = [
    {
      name: 'Fast Recognition',
      description: 'Our system identifies students in seconds with 99.8% accuracy, even in crowded classrooms.',
      icon: 'fas fa-user-clock',
    },
    {
      name: 'Time Saving',
      description: 'Eliminate manual roll calls and save up to 15 minutes per class on attendance tracking.',
      icon: 'fas fa-stopwatch',
    },
    {
      name: 'Multi-class Support',
      description: 'Easily manage attendance for multiple classes and sections from a single dashboard.',
      icon: 'fas fa-users-class',
    },
    {
      name: 'Secure & Private',
      description: 'All facial data is encrypted and stored securely with strict privacy controls.',
      icon: 'fas fa-shield-alt',
    },
  ]
  </script>
  
  <style scoped>
  /* Add any custom styles here if needed */
  </style>