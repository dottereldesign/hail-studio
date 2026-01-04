<script setup>
import { computed, nextTick, onBeforeUnmount, ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { pushToast } from '@/lib/toast';

const props = defineProps({
    open: {
        type: Boolean,
        default: false,
    },
    categories: {
        type: Array,
        default: () => [],
    },
    selectedCategoryId: {
        type: Number,
        default: null,
    },
});

const emit = defineEmits(['close']);

const nameInput = ref(null);
const fileInput = ref(null);
const modalRef = ref(null);
const previewUrl = ref('');
const dragActive = ref(false);
const payloadError = ref('');

const defaultCategoryId = () => props.selectedCategoryId ?? props.categories[0]?.id ?? null;

const form = useForm({
    name: '',
    component_category_id: defaultCategoryId(),
    payload: '',
    screenshot: null,
});

const resetState = () => {
    form.clearErrors();
    form.name = '';
    form.payload = '';
    form.screenshot = null;
    form.component_category_id = defaultCategoryId();
    payloadError.value = '';
    dragActive.value = false;

    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
        previewUrl.value = '';
    }
};

const handleGlobalKeydown = (event) => {
    if (!props.open) {
        return;
    }

    if (event.key === 'Escape') {
        emit('close');
    }
};

const focusFirstField = () => {
    nextTick(() => {
        nameInput.value?.focus();
    });
};

watch(
    () => props.open,
    (open) => {
        if (open) {
            form.component_category_id = defaultCategoryId();
            focusFirstField();
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

const setScreenshot = (file) => {
    if (!file) {
        return;
    }

    form.screenshot = file;

    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
    }

    previewUrl.value = URL.createObjectURL(file);
};

const handleFileChange = (event) => {
    const file = event.target.files?.[0];
    setScreenshot(file);
};

const handleDrop = (event) => {
    dragActive.value = false;
    const file = event.dataTransfer?.files?.[0];
    setScreenshot(file);
};

const formatJson = () => {
    payloadError.value = '';

    try {
        const parsed = JSON.parse(form.payload);
        form.payload = JSON.stringify(parsed, null, 2);
    } catch (error) {
        payloadError.value = 'Invalid JSON. Please fix before saving.';
    }
};

const payloadErrorMessage = computed(() => payloadError.value || form.errors.payload);
const submitDisabled = computed(() => form.processing || !!payloadError.value);

const submit = () => {
    payloadError.value = '';

    try {
        const parsed = JSON.parse(form.payload);
        form.payload = JSON.stringify(parsed, null, 2);
    } catch (error) {
        payloadError.value = 'Invalid JSON. Please fix before saving.';
        return;
    }

    form.post('/components', {
        forceFormData: true,
        onSuccess: () => {
            pushToast('Component created.');
            emit('close');
        },
        onError: () => {
            pushToast('Please fix the errors and try again.', 'error');
        },
    });
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
        <div v-if="open" class="fixed inset-0 z-50 bg-slate-950/80 px-6 py-10" @click.self="$emit('close')">
            <div
                ref="modalRef"
                class="mx-auto w-full max-w-2xl rounded-3xl border border-slate-800 bg-slate-900/95 p-8 text-slate-100 shadow-xl"
                role="dialog"
                aria-modal="true"
                aria-labelledby="add-component-title"
                @keydown="trapFocus"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Components</p>
                        <h2 id="add-component-title" class="mt-2 text-2xl font-semibold">Create Component</h2>
                    </div>
                    <button
                        type="button"
                        class="rounded-full border border-slate-700 px-3 py-2 text-xs uppercase tracking-[0.2em] text-slate-200"
                        @click="$emit('close')"
                    >
                        Close
                    </button>
                </div>

                <form class="mt-6 space-y-6" @submit.prevent="submit">
                    <div>
                        <label class="text-xs uppercase tracking-[0.2em] text-slate-400">Name</label>
                        <input
                            ref="nameInput"
                            v-model="form.name"
                            type="text"
                            class="mt-2 w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-2 text-sm text-slate-100 focus:border-slate-500 focus:outline-none"
                            placeholder="e.g. Pricing Sections 3"
                            required
                        >
                        <p v-if="form.errors.name" class="mt-2 text-xs text-rose-300">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <div>
                        <label class="text-xs uppercase tracking-[0.2em] text-slate-400">Category</label>
                        <select
                            v-model.number="form.component_category_id"
                            class="mt-2 w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-2 text-sm text-slate-100 focus:border-slate-500 focus:outline-none"
                            required
                        >
                            <option
                                v-for="category in categories"
                                :key="category.id"
                                :value="category.id"
                            >
                                {{ category.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.component_category_id" class="mt-2 text-xs text-rose-300">
                            {{ form.errors.component_category_id }}
                        </p>
                    </div>

                    <div>
                        <label class="text-xs uppercase tracking-[0.2em] text-slate-400">Screenshot</label>
                        <div
                            class="mt-2 flex cursor-pointer items-center gap-4 rounded-2xl border border-dashed border-slate-700 bg-slate-950/60 p-4 transition"
                            :class="dragActive ? 'border-slate-400 bg-slate-900/60' : ''"
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
                            <div
                                class="flex h-20 w-28 items-center justify-center overflow-hidden rounded-xl border border-slate-800 bg-slate-900"
                            >
                                <img
                                    v-if="previewUrl"
                                    :src="previewUrl"
                                    alt="Screenshot preview"
                                    class="h-full w-full object-cover"
                                >
                                <span v-else class="text-xs text-slate-500">Preview</span>
                            </div>
                            <div class="text-sm text-slate-300">
                                <p class="font-semibold text-slate-100">Drop an image here</p>
                                <p class="text-xs text-slate-500">or click to upload (PNG, JPG, WEBP)</p>
                            </div>
                        </div>
                        <p v-if="form.errors.screenshot" class="mt-2 text-xs text-rose-300">
                            {{ form.errors.screenshot }}
                        </p>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label class="text-xs uppercase tracking-[0.2em] text-slate-400">JSON Payload</label>
                            <button
                                type="button"
                                class="rounded-full border border-slate-700 px-3 py-1 text-[10px] uppercase tracking-[0.2em] text-slate-200"
                                @click="formatJson"
                            >
                                Format JSON
                            </button>
                        </div>
                        <textarea
                            v-model="form.payload"
                            class="mt-2 min-h-[180px] w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 font-mono text-xs text-slate-100 focus:border-slate-500 focus:outline-none"
                            placeholder='{"name": "Pricing Sections 3", "layout": {"variant": "default"}}'
                            required
                            @input="payloadError = ''"
                        />
                        <p v-if="payloadErrorMessage" class="mt-2 text-xs text-rose-300">
                            {{ payloadErrorMessage }}
                        </p>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <button
                            type="button"
                            class="rounded-full border border-slate-700 px-4 py-2 text-xs uppercase tracking-[0.2em] text-slate-200"
                            @click="$emit('close')"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="rounded-full border border-slate-600 bg-white/10 px-5 py-2 text-xs uppercase tracking-[0.3em] text-white transition hover:border-slate-400 hover:bg-white/20 disabled:cursor-not-allowed disabled:opacity-60"
                            :disabled="submitDisabled"
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
