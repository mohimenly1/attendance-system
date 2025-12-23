<script setup>
import Checkbox from '@/components/Checkbox.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import TextInput from '@/components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div
            v-if="status"
            class="mb-4 rounded-xl border border-cyan-400/40 bg-slate-900/70 px-4 py-3 text-sm font-medium text-cyan-300"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <!-- Email -->
            <div>
                <InputLabel
                    for="email"
                    value="Email"
                    class="text-xs font-semibold tracking-wide text-cyan-200"
                />

                <div class="mt-2 relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-xs text-cyan-300/80">
                        @
                    </span>

                    <TextInput
                        id="email"
                        type="email"
                        class="block w-full rounded-full border border-cyan-500/50 bg-slate-900/80 px-9 py-2.5 text-sm text-slate-100 shadow-[0_0_12px_rgba(37,99,235,0.45)] outline-none transition focus:border-cyan-300 focus:ring-2 focus:ring-cyan-400/70"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />
                </div>

                <InputError class="mt-2 text-xs text-rose-400" :message="form.errors.email" />
            </div>

            <!-- Password -->
            <div>
                <InputLabel
                    for="password"
                    value="Password"
                    class="text-xs font-semibold tracking-wide text-cyan-200"
                />

                <div class="mt-2 relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-xs text-cyan-300/80">
                        *
                    </span>

                    <TextInput
                        id="password"
                        type="password"
                        class="block w-full rounded-full border border-cyan-500/50 bg-slate-900/80 px-9 py-2.5 text-sm text-slate-100 shadow-[0_0_12px_rgba(37,99,235,0.45)] outline-none transition focus:border-cyan-300 focus:ring-2 focus:ring-cyan-400/70"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                    />
                </div>

                <InputError class="mt-2 text-xs text-rose-400" :message="form.errors.password" />
            </div>

            <!-- Remember + Forgot -->
            <div class="flex items-center justify-between mt-1">
                <label class="flex items-center text-xs font-medium text-slate-300">
                    <Checkbox
                        name="remember"
                        v-model:checked="form.remember"
                        class="h-4 w-4 rounded-full border-cyan-400/80 bg-transparent text-cyan-400 focus:ring-cyan-400/70"
                    />
                    <span class="ms-2">Remember me</span>
                </label>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-xs font-medium text-cyan-300 underline-offset-2 hover:text-cyan-200 hover:underline"
                >
                    Forgot your password?
                </Link>
            </div>

            <!-- Button -->
            <div class="mt-4 flex justify-center">
                <PrimaryButton
                    :disabled="form.processing"
                    class="rounded-full bg-gradient-to-r from-indigo-500 via-blue-500 to-cyan-400 px-8 py-2.5 text-sm font-semibold uppercase tracking-wide text-white shadow-[0_0_25px_rgba(56,189,248,0.8)] transition hover:shadow-[0_0_35px_rgba(56,189,248,1)] hover:brightness-110 active:scale-95"
                    :class="{ 'opacity-25 cursor-not-allowed': form.processing }"
                >
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
