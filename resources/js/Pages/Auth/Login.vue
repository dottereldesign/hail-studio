<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

const logoUrl = "/brand/logo.png";

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post("/login");
};
</script>

<template>
    <AppLayout>
        <Head title="Log in" />
        <section class="min-h-[calc(100vh-4rem)] px-6 py-8">
            <div
                class="mx-auto min-h-[calc(100vh-4rem-4rem)] max-w-[1440px] lg:grid lg:grid-cols-2 lg:gap-10"
            >
                <div
                    class="flex w-full flex-col lg:h-[calc(100vh-4rem-4rem)] lg:justify-center"
                >
                    <div
                        class="flex h-full w-full flex-col justify-center rounded-3xl border border-[var(--color-border)] bg-[var(--color-surface-muted)] p-8 shadow-lg"
                    >
                        <div
                            class="flex items-center justify-center gap-4 text-3xl font-semibold tracking-wide"
                        >
                            <img
                                :src="logoUrl"
                                alt="hail Studio logo"
                                class="h-10 w-10"
                            />
                            <span
                                ><span class="text-[#72AF2E]">hail</span>
                                Studio</span
                            >
                        </div>
                        <form class="mt-6 space-y-4" @submit.prevent="submit">
                            <div>
                                <label
                                    class="text-xs uppercase tracking-[0.2em] text-[var(--color-muted)]"
                                    >Email</label
                                >
                                <input
                                    v-model="form.email"
                                    type="email"
                                    class="mt-2 w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface)] px-4 py-2 text-sm text-[var(--color-text)] focus:border-[var(--color-border-strong)] focus:outline-none"
                                    required
                                    autocomplete="email"
                                />
                                <p
                                    v-if="form.errors.email"
                                    class="form-error mt-2 text-xs"
                                >
                                    {{ form.errors.email }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="text-xs uppercase tracking-[0.2em] text-[var(--color-muted)]"
                                    >Password</label
                                >
                                <input
                                    v-model="form.password"
                                    type="password"
                                    class="mt-2 w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface)] px-4 py-2 text-sm text-[var(--color-text)] focus:border-[var(--color-border-strong)] focus:outline-none"
                                    required
                                    autocomplete="current-password"
                                />
                            </div>
                            <div
                                class="flex items-center justify-between text-xs text-[var(--color-muted)]"
                            >
                                <label class="flex items-center gap-2">
                                    <input
                                        v-model="form.remember"
                                        type="checkbox"
                                        class="h-4 w-4 rounded border-[var(--color-border)] bg-[var(--color-surface)] text-[var(--color-text)]"
                                    />
                                    Remember me
                                </label>
                            </div>
                            <button
                                type="submit"
                                class="btn btn--primary w-full px-4 py-4 text-xs uppercase tracking-[0.3em] mt-6"
                                :disabled="form.processing"
                            >
                                Log in
                            </button>
                        </form>
                    </div>
                </div>
                <div class="relative hidden lg:block">
                    <div
                        class="h-[calc(100vh-4rem-4rem)] overflow-hidden rounded-3xl border border-[var(--color-border)] bg-[var(--color-surface-muted)]"
                    >
                        <img
                            src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1600&q=80"
                            alt="Studio workspace"
                            class="h-full w-full object-cover"
                            loading="lazy"
                        />
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
