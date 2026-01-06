<script setup>
import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { pushToast } from '@/lib/toast';

const props = defineProps({
    component: {
        type: Object,
        required: true,
    },
    page: {
        type: Number,
        default: null,
    },
    showDelete: {
        type: Boolean,
        default: false,
    },
});

const page = usePage();
const allowedRoles = ['OWNER', 'ADMIN', 'EDITOR'];
const canManage = computed(() => allowedRoles.includes(page.props.auth?.user?.role ?? ''));
const payloadCache = ref(null);
const isCopying = ref(false);
const isDeleting = ref(false);
const isPreviewOpen = ref(false);

const openPreview = () => {
    isPreviewOpen.value = true;
};

const closePreview = () => {
    isPreviewOpen.value = false;
};

const fetchPayload = async () => {
    if (payloadCache.value) {
        return payloadCache.value;
    }

    const response = await fetch(`/components/${props.component.id}/payload`, {
        headers: {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    });

    if (!response.ok) {
        throw new Error('Payload fetch failed');
    }

    const data = await response.json();
    payloadCache.value = data.payload;
    return payloadCache.value;
};

const copyPayload = async () => {
    if (isCopying.value) {
        return;
    }

    isCopying.value = true;

    try {
        const payload = JSON.stringify(await fetchPayload(), null, 2);
        await navigator.clipboard.writeText(payload);
        pushToast('Copied JSON to clipboard.');
    } catch (error) {
        pushToast('Could not copy. Please try again.', 'error');
    } finally {
        isCopying.value = false;
    }
};

const deleteComponent = () => {
    if (!canManage.value || isDeleting.value) {
        return;
    }

    if (!window.confirm('Delete this component? This cannot be undone.')) {
        return;
    }

    isDeleting.value = true;

    const pageQuery = props.page ? `?page=${props.page}` : '';

    router.delete(`/components/${props.component.id}${pageQuery}`, {
        preserveScroll: true,
        onSuccess: () => {
            pushToast('Component deleted.');
        },
        onError: () => {
            pushToast('Could not delete component.', 'error');
        },
        onFinish: () => {
            isDeleting.value = false;
        },
    });
};
</script>

<template>
    <div class="relative rounded-2xl border border-[var(--color-border)] bg-[var(--color-card)] p-4 shadow-sm">
        <div
            class="aspect-[4/3] overflow-hidden rounded-xl border border-[var(--color-border)] bg-[var(--color-surface)]"
        >
            <img
                :src="component.image_url"
                :alt="component.name"
                class="h-full w-full object-cover cursor-zoom-in"
                @click="openPreview"
            >
        </div>
        <div class="mt-4 flex items-center justify-between gap-3">
            <p class="text-sm font-semibold text-[var(--color-text)]">
                {{ component.name }}
            </p>
            <div class="flex items-center gap-2">
                <button
                    type="button"
                    class="btn btn--secondary px-3 py-1 text-xs uppercase tracking-[0.2em] disabled:cursor-not-allowed disabled:opacity-60"
                    :disabled="isCopying"
                    @click="copyPayload"
                >
                    <span class="flex items-center gap-2">
                        <i class="fa-regular fa-clipboard text-[11px]"></i>
                        Copy
                    </span>
                </button>
            </div>
        </div>
        <button
            v-if="showDelete && canManage"
            type="button"
            class="absolute right-3 top-3 flex h-8 w-8 items-center justify-center rounded-full border border-[var(--danger-border)] bg-[var(--danger-bg)] text-[var(--danger-fg)] transition hover:scale-[1.03] disabled:cursor-not-allowed disabled:opacity-60"
            :disabled="isDeleting"
            @click="deleteComponent"
        >
            <i class="fa-solid fa-minus text-[12px]"></i>
        </button>
    </div>
    <div
        v-if="isPreviewOpen"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-6"
        @click.self="closePreview"
    >
        <div class="relative w-full max-w-4xl">
            <button
                type="button"
                class="absolute right-4 top-4 rounded-full border border-white/30 bg-black/60 px-3 py-2 text-xs uppercase tracking-[0.25em] text-white"
                @click="closePreview"
            >
                Close
            </button>
            <img
                :src="component.image_url"
                :alt="component.name"
                class="max-h-[80vh] w-full rounded-2xl border border-white/20 object-contain bg-black"
            >
        </div>
    </div>
</template>
