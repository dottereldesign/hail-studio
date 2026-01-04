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
            <div class="w-full max-w-md rounded-3xl border border-slate-800 bg-slate-900/60 p-8 shadow-lg">
                <h1 class="text-2xl font-semibold text-white">Log in</h1>
                <p class="mt-2 text-sm text-slate-400">
                    Access the Components Library.
                </p>

                <form class="mt-6 space-y-4" @submit.prevent="submit">
                    <div>
                        <label class="text-xs uppercase tracking-[0.2em] text-slate-400">Email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            class="mt-2 w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-2 text-sm text-slate-100 focus:border-slate-500 focus:outline-none"
                            required
                            autocomplete="email"
                        >
                        <p v-if="form.errors.email" class="mt-2 text-xs text-rose-300">
                            {{ form.errors.email }}
                        </p>
                    </div>
                    <div>
                        <label class="text-xs uppercase tracking-[0.2em] text-slate-400">Password</label>
                        <input
                            v-model="form.password"
                            type="password"
                            class="mt-2 w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-2 text-sm text-slate-100 focus:border-slate-500 focus:outline-none"
                            required
                            autocomplete="current-password"
                        >
                    </div>
                    <div class="flex items-center justify-between text-xs text-slate-400">
                        <label class="flex items-center gap-2">
                            <input
                                v-model="form.remember"
                                type="checkbox"
                                class="h-4 w-4 rounded border-slate-600 bg-slate-950 text-slate-200"
                            >
                            Remember me
                        </label>
                    </div>
                    <button
                        type="submit"
                        class="w-full rounded-full border border-slate-600 bg-white/10 px-4 py-2 text-xs uppercase tracking-[0.3em] text-white transition hover:border-slate-400 hover:bg-white/20"
                        :disabled="form.processing"
                    >
                        Log in
                    </button>
                </form>
            </div>
        </section>
    </AppLayout>
</template>
