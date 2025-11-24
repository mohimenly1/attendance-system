<script setup>
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen flex-col items-center bg-bg text-text pt-6 sm:justify-center sm:pt-0">
        <div>
            <Link href="/">
                <ApplicationLogo class="h-20 w-20 fill-current text-muted" />
            </Link>
        </div>

        <div class="relative mt-6 w-full overflow-hidden px-6 py-6 sm:max-w-md glass-card">
            <slot />
        </div>
    </div>
</template>
