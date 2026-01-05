<script setup>
import { computed, ref } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { pushToast } from '@/lib/toast';

const page = usePage();
const user = computed(() => page.props.auth?.user ?? {});

const previewUrl = ref('');
const fileInput = ref(null);

const form = useForm({
    name: user.value?.name ?? '',
    avatar: null,
});

const roleLabel = computed(() => user.value?.role ?? 'USER');

const openFileDialog = () => {
    fileInput.value?.click();
};

const handleAvatarChange = (event) => {
    const file = event.target.files?.[0];
    if (!file) {
        return;
    }

    form.avatar = file;

    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
    }

    previewUrl.value = URL.createObjectURL(file);
};

const submit = () => {
    form.post('/dashboard', {
        forceFormData: true,
        onSuccess: () => {
            pushToast('Profile updated.');
        },
        onError: () => {
            pushToast('Please fix the errors and try again.', 'error');
        },
    });
};
</script>

<template>
    <DashboardLayout>
        <Head title="Dashboard" />
        <div class="space-y-6">
            <div class="rounded-3xl border border-[var(--color-border)] bg-[var(--color-card)] p-8">
                <div class="flex flex-wrap items-center justify-between gap-6">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]">Profile</p>
                        <h1 class="mt-2 text-3xl font-semibold text-[var(--color-text)]">Dashboard</h1>
                        <p class="mt-2 text-sm text-[var(--color-muted)]">
                            Manage your profile details and avatar.
                        </p>
                    </div>
                    <span class="badge badge--secondary">
                        {{ roleLabel }}
                    </span>
                </div>
            </div>

            <form class="grid gap-6 lg:grid-cols-[0.8fr_1.2fr]" @submit.prevent="submit">
                <div class="rounded-3xl border border-[var(--color-border)] bg-[var(--color-card)] p-6">
                    <p class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]">Avatar</p>
                    <div class="mt-5 flex items-center gap-4">
                        <div class="h-20 w-20 overflow-hidden rounded-full border border-[var(--color-border)] bg-[var(--color-surface)]">
                            <img
                                v-if="previewUrl || user.avatarUrl"
                                :src="previewUrl || user.avatarUrl"
                                alt="Avatar"
                                class="h-full w-full object-cover"
                            >
                        </div>
                        <div class="space-y-2">
                            <button
                                type="button"
                                class="btn btn--secondary px-4 py-2 text-xs uppercase tracking-[0.2em]"
                                @click="openFileDialog"
                            >
                                Update Avatar
                            </button>
                            <input
                                ref="fileInput"
                                type="file"
                                class="hidden"
                                accept="image/png,image/jpeg,image/jpg,image/webp"
                                @change="handleAvatarChange"
                            >
                            <p class="text-xs text-[var(--color-muted)]">
                                PNG, JPG, or WEBP up to 4MB.
                            </p>
                        </div>
                    </div>
                    <p v-if="form.errors.avatar" class="form-error mt-2 text-xs">
                        {{ form.errors.avatar }}
                    </p>
                </div>

                <div class="rounded-3xl border border-[var(--color-border)] bg-[var(--color-card)] p-6">
                    <p class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]">Details</p>
                    <div class="mt-5 space-y-4">
                        <div>
                            <label class="text-xs uppercase tracking-[0.2em] text-[var(--color-muted)]">Name</label>
                            <input
                                v-model="form.name"
                                type="text"
                                class="mt-2 w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface)] px-4 py-2 text-sm text-[var(--color-text)]"
                                required
                            >
                            <p v-if="form.errors.name" class="form-error mt-2 text-xs">
                                {{ form.errors.name }}
                            </p>
                        </div>
                        <div>
                            <label class="text-xs uppercase tracking-[0.2em] text-[var(--color-muted)]">Email</label>
                            <input
                                :value="user.email"
                                type="email"
                                class="mt-2 w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface)] px-4 py-2 text-sm text-[var(--color-muted)]"
                                disabled
                            >
                        </div>
                        <div class="flex items-center justify-end">
                            <button
                                type="submit"
                                class="btn btn--primary px-5 py-2 text-xs uppercase tracking-[0.3em]"
                                :disabled="form.processing"
                            >
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </DashboardLayout>
</template>
