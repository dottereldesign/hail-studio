<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login');
};
</script>

<template>
    <AppLayout>
        <Head title="Log in" />
        <section class="flex min-h-[calc(100vh-4rem)] items-center justify-center px-6">
            <div class="w-full max-w-md rounded-3xl border border-[var(--color-border)] bg-[var(--color-surface-muted)] p-8 shadow-lg">
                <h1 class="text-2xl font-semibold text-[var(--color-text)]">Log in</h1>
                <p class="mt-2 text-sm text-[var(--color-muted)]">
                    Access the Components Library.
                </p>

                <form class="mt-6 space-y-4" @submit.prevent="submit">
                    <div>
                        <label class="text-xs uppercase tracking-[0.2em] text-[var(--color-muted)]">Email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            class="mt-2 w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface)] px-4 py-2 text-sm text-[var(--color-text)] focus:border-[var(--color-border-strong)] focus:outline-none"
                            required
                            autocomplete="email"
                        >
                        <p v-if="form.errors.email" class="form-error mt-2 text-xs">
                            {{ form.errors.email }}
                        </p>
                    </div>
                    <div>
                        <label class="text-xs uppercase tracking-[0.2em] text-[var(--color-muted)]">Password</label>
                        <input
                            v-model="form.password"
                            type="password"
                            class="mt-2 w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface)] px-4 py-2 text-sm text-[var(--color-text)] focus:border-[var(--color-border-strong)] focus:outline-none"
                            required
                            autocomplete="current-password"
                        >
                    </div>
                    <div class="flex items-center justify-between text-xs text-[var(--color-muted)]">
                        <label class="flex items-center gap-2">
                            <input
                                v-model="form.remember"
                                type="checkbox"
                                class="h-4 w-4 rounded border-[var(--color-border)] bg-[var(--color-surface)] text-[var(--color-text)]"
                            >
                            Remember me
                        </label>
                    </div>
                    <button
                        type="submit"
                        class="btn btn--primary w-full px-4 py-2 text-xs uppercase tracking-[0.3em]"
                        :disabled="form.processing"
                    >
                        Log in
                    </button>
                </form>
            </div>
        </section>
    </AppLayout>
</template>
