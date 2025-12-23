<script setup>
import ApplicationLogo from '@/components/ApplicationLogo.vue'
import { Link } from '@inertiajs/vue3'
</script>

<template>
    <div
        class="min-h-screen w-full flex flex-col items-center justify-center relative overflow-hidden"
        style="background: radial-gradient(circle at top, #0f172a 0%, #020617 55%, #000814 100%);"
    >
        <!-- الخلفيات الضوئية -->
        <div
            class="pointer-events-none absolute -left-32 -top-32 h-72 w-72 rounded-full blur-3xl opacity-40"
            style="background: radial-gradient(circle, #22d3ee 0%, transparent 60%);"
        ></div>

        <div
            class="pointer-events-none absolute -right-24 top-1/3 h-72 w-72 rounded-full blur-3xl opacity-40"
            style="background: radial-gradient(circle, #3b82f6 0%, transparent 60%);"
        ></div>

        <div
            class="pointer-events-none absolute -bottom-32 left-1/4 h-80 w-80 rounded-full blur-3xl opacity-30"
            style="background: radial-gradient(circle, #0ea5e9 0%, transparent 60%);"
        ></div>

        <!-- دائرة تسجيل الدخول بخلفية زجاجية -->
        <div
            class="h-[470px] w-[470px] rounded-full border shadow-2xl flex items-center justify-center login-card-animate"
            style="
                background: rgba(255, 255, 255, 0.06);
                backdrop-filter: blur(28px) saturate(180%);
                -webkit-backdrop-filter: blur(28px) saturate(180%);
                border: 1px solid rgba(255, 255, 255, 0.22);
                box-shadow:
                    0 0 25px rgba(56,189,248,0.40),
                    0 0 55px rgba(37,99,235,0.35),
                    inset 0 0 18px rgba(255,255,255,0.10);
            "
        >
            <div class="flex flex-col items-center w-full px-10">
                <!-- الشعار داخل الدائرة -->
                <Link href="/" class="mb-8">
                    <ApplicationLogo
                        class="h-16 w-16 text-cyan-400 drop-shadow-[0_0_6px_rgba(34,211,238,0.45)]"
                    />
                </Link>

                <!-- نموذج تسجيل الدخول -->
                <div class="w-full">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes softFadeInUp {
    0% {
        opacity: 0;
        transform: translateY(26px) scale(0.97);
    }
    60% {
        opacity: 1;
        transform: translateY(-4px) scale(1);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

/* حركة انسيابية متدرجة للبطاقة عند تحميل الصفحة */
.login-card-animate {
    opacity: 0;
    animation: softFadeInUp 0.9s ease-out forwards;
}
</style>
