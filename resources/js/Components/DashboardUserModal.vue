<script setup>
import { computed, nextTick, onBeforeUnmount, ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { pushToast } from '@/lib/toast';

const props = defineProps({
    open: {
        type: Boolean,
        default: false,
    },
    mode: {
        type: String,
        default: 'create',
    },
    user: {
        type: Object,
        default: null,
    },
    roles: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(['close']);

const modalRef = ref(null);
const fileInput = ref(null);
const previewUrl = ref('');
const showPassword = ref(false);
const dragActive = ref(false);

const defaultRole = () => props.roles[0] ?? 'VIEWER';

const form = useForm({
    name: '',
    email: '',
    role: defaultRole(),
    password: '',
    avatar: null,
});

const isEdit = computed(() => props.mode === 'edit');
const avatarPreview = computed(() => previewUrl.value || props.user?.avatarUrl || '');

const resetState = () => {
    form.clearErrors();
    form.name = '';
    form.email = '';
    form.role = defaultRole();
    form.password = '';
    form.avatar = null;
    showPassword.value = false;
    dragActive.value = false;

    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
        previewUrl.value = '';
    }
};

const fillForm = () => {
    if (!props.user) {
        resetState();
        return;
    }

    form.name = props.user.name ?? '';
    form.email = props.user.email ?? '';
    form.role = props.user.role ?? defaultRole();
};

const handleGlobalKeydown = (event) => {
    if (!props.open) {
        return;
    }

    if (event.key === 'Escape') {
        emit('close');
    }
};

watch(
    () => props.open,
    (open) => {
        if (open) {
            fillForm();
            nextTick(() => {
                modalRef.value?.querySelector('input')?.focus();
            });
            window.addEventListener('keydown', handleGlobalKeydown);
        } else {
            window.removeEventListener('keydown', handleGlobalKeydown);
            resetState();
        }
    }
);

onBeforeUnmount(() => {
    window.removeEventListener('keydown', handleGlobalKeydown);
    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
    }
});

const openFileDialog = () => {
    fileInput.value?.click();
};

const setAvatar = (file) => {
    if (!file) {
        return;
    }

    form.avatar = file;

    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
    }

    previewUrl.value = URL.createObjectURL(file);
};

const handleFileChange = (event) => {
    const file = event.target.files?.[0];
    setAvatar(file);
};

const handleDrop = (event) => {
    dragActive.value = false;
    const file = event.dataTransfer?.files?.[0];
    setAvatar(file);
};

const generatePassword = () => {
    const charset = 'abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789!@#$';
    const length = 12;
    let value = '';

    for (let index = 0; index < length; index += 1) {
        value += charset[Math.floor(Math.random() * charset.length)];
    }

    form.password = value;
    showPassword.value = true;
};

const submit = () => {
    const options = {
        forceFormData: true,
        onSuccess: () => {
            pushToast(isEdit.value ? 'User updated.' : 'User created.');
            emit('close');
        },
        onError: () => {
            pushToast('Please fix the errors and try again.', 'error');
        },
    };

    if (isEdit.value) {
        form.patch(`/dashboard/users/${props.user.id}`, options);
    } else {
        form.post('/dashboard/users', options);
    }
};

const trapFocus = (event) => {
    if (event.key !== 'Tab' || !modalRef.value) {
        return;
    }

    const focusable = modalRef.value.querySelectorAll(
        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
    );

    if (!focusable.length) {
        return;
    }

    const first = focusable[0];
    const last = focusable[focusable.length - 1];
    const isShift = event.shiftKey;

    if (isShift && document.activeElement === first) {
        event.preventDefault();
        last.focus();
    } else if (!isShift && document.activeElement === last) {
        event.preventDefault();
        first.focus();
    }
};
</script>

<template>
    <transition name="fade">
        <div v-if="open" class="fixed inset-0 z-50 bg-[var(--color-bg-soft)] px-6 py-10" @click.self="$emit('close')">
            <div
                ref="modalRef"
                class="mx-auto w-full max-w-2xl rounded-3xl border border-[var(--color-border)] bg-[var(--color-surface-muted)] p-8 text-[var(--color-text)] shadow-xl"
                role="dialog"
                aria-modal="true"
                @keydown="trapFocus"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]">Users</p>
                        <h2 class="mt-2 text-2xl font-semibold">
                            {{ isEdit ? 'Edit User' : 'Create User' }}
                        </h2>
                    </div>
                    <button
                        type="button"
                        class="btn btn--ghost px-3 py-2 text-xs uppercase tracking-[0.2em]"
                        @click="$emit('close')"
                    >
                        Close
                    </button>
                </div>

                <form class="mt-6 space-y-6" @submit.prevent="submit">
                    <div class="grid gap-4 sm:grid-cols-2">
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
                                v-model="form.email"
                                type="email"
                                class="mt-2 w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface)] px-4 py-2 text-sm text-[var(--color-text)]"
                                required
                            >
                            <p v-if="form.errors.email" class="form-error mt-2 text-xs">
                                {{ form.errors.email }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs uppercase tracking-[0.2em] text-[var(--color-muted)]">Role</label>
                        <select
                            v-model="form.role"
                            class="mt-2 w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface)] px-4 py-2 text-sm text-[var(--color-text)]"
                            required
                        >
                            <option v-for="role in roles" :key="role" :value="role">
                                {{ role }}
                            </option>
                        </select>
                        <p v-if="form.errors.role" class="form-error mt-2 text-xs">
                            {{ form.errors.role }}
                        </p>
                    </div>

                    <div>
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <label class="text-xs uppercase tracking-[0.2em] text-[var(--color-muted)]">
                                Password {{ isEdit ? '(optional)' : '' }}
                            </label>
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    class="btn btn--ghost px-3 py-1 text-[10px] uppercase tracking-[0.2em]"
                                    @click="generatePassword"
                                >
                                    Generate
                                </button>
                                <button
                                    type="button"
                                    class="btn btn--ghost px-3 py-1 text-[10px] uppercase tracking-[0.2em]"
                                    @click="showPassword = !showPassword"
                                >
                                    {{ showPassword ? 'Hide' : 'Show' }}
                                </button>
                            </div>
                        </div>
                        <input
                            v-model="form.password"
                            :type="showPassword ? 'text' : 'password'"
                            class="mt-2 w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-surface)] px-4 py-2 text-sm text-[var(--color-text)]"
                            :placeholder="isEdit ? 'Leave blank to keep current password' : 'Set an initial password'"
                            :required="!isEdit"
                        >
                        <p v-if="form.errors.password" class="form-error mt-2 text-xs">
                            {{ form.errors.password }}
                        </p>
                        <p class="mt-2 text-xs text-[var(--color-muted)]">
                            Passwords are only shown here. They will not be displayed again.
                        </p>
                    </div>

                    <div>
                        <label class="text-xs uppercase tracking-[0.2em] text-[var(--color-muted)]">Avatar</label>
                        <div
                            class="mt-2 flex cursor-pointer items-center gap-4 rounded-2xl border border-dashed border-[var(--color-border)] bg-[var(--color-surface-muted)] p-4 transition"
                            :class="dragActive ? 'border-[var(--color-border-strong)] bg-[var(--color-surface)]' : ''"
                            @click="openFileDialog"
                            @dragover.prevent="dragActive = true"
                            @dragleave.prevent="dragActive = false"
                            @drop.prevent="handleDrop"
                        >
                            <input
                                ref="fileInput"
                                type="file"
                                class="hidden"
                                accept="image/png,image/jpeg,image/jpg,image/webp"
                                @change="handleFileChange"
                            >
                            <div class="flex h-16 w-16 items-center justify-center overflow-hidden rounded-full border border-[var(--color-border)] bg-[var(--color-surface)]">
                                <img
                                    v-if="avatarPreview"
                                    :src="avatarPreview"
                                    alt="Avatar preview"
                                    class="h-full w-full object-cover"
                                >
                                <span v-else class="text-xs text-[var(--color-muted-soft)]">Preview</span>
                            </div>
                            <div class="text-sm text-[var(--color-muted)]">
                                <p class="font-semibold text-[var(--color-text)]">Drop an image here</p>
                                <p class="text-xs text-[var(--color-muted-soft)]">or click to upload (PNG, JPG, WEBP)</p>
                            </div>
                        </div>
                        <p v-if="form.errors.avatar" class="form-error mt-2 text-xs">
                            {{ form.errors.avatar }}
                        </p>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <button
                            type="button"
                            class="btn btn--ghost px-4 py-2 text-xs uppercase tracking-[0.2em]"
                            @click="$emit('close')"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="btn btn--primary px-5 py-2 text-xs uppercase tracking-[0.3em] disabled:cursor-not-allowed disabled:opacity-60"
                            :disabled="form.processing"
                        >
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 150ms ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
