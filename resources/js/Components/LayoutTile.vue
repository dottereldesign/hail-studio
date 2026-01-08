<script setup>
import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { pushToast } from '@/lib/toast';

const props = defineProps({
    layout: {
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
const isDownloading = ref(false);
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

    const response = await fetch(`/library/${props.layout.id}/payload`, {
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

const toFileName = (value) => {
    return (
        value
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)/g, '') || 'layout'
    );
};

const downloadPayload = async () => {
    if (isDownloading.value) {
        return;
    }

    isDownloading.value = true;

    try {
        const payload = JSON.stringify(await fetchPayload(), null, 2);
        const blob = new Blob([payload], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        const baseName = props.layout.slug || props.layout.name || 'layout';
        link.href = url;
        link.download = `${toFileName(baseName)}.json`;
        document.body.appendChild(link);
        link.click();
        link.remove();
        URL.revokeObjectURL(url);
        pushToast('Download started.');
    } catch (error) {
        pushToast('Could not download. Please try again.', 'error');
    } finally {
        isDownloading.value = false;
    }
};

const deleteLayout = () => {
    if (!canManage.value || isDeleting.value) {
        return;
    }

    if (!window.confirm('Delete this layout? This cannot be undone.')) {
        return;
    }

    isDeleting.value = true;

    const pageQuery = props.page ? `?page=${props.page}` : '';

    router.delete(`/library/${props.layout.id}${pageQuery}`, {
        preserveScroll: true,
        onSuccess: () => {
            pushToast('Layout deleted.');
        },
        onError: () => {
            pushToast('Could not delete layout.', 'error');
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
                :src="layout.image_url"
                :alt="layout.name"
                class="h-full w-full object-cover cursor-zoom-in"
                @click="openPreview"
            >
        </div>
        <div class="mt-4 flex items-center justify-between gap-3">
            <p class="text-sm font-semibold text-[var(--color-text)]">
                {{ layout.name }}
            </p>
            <div class="flex items-center gap-2">
                <button
                    type="button"
                    class="btn btn--secondary px-3 py-1 text-xs uppercase tracking-[0.2em] disabled:cursor-not-allowed disabled:opacity-60"
                    :disabled="isDownloading"
                    @click="downloadPayload"
                >
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-download text-[11px]"></i>
                        Download
                    </span>
                </button>
            </div>
        </div>
        <button
            v-if="showDelete && canManage"
            type="button"
            class="absolute right-3 top-3 flex h-8 w-8 items-center justify-center rounded-full border border-[var(--danger-border)] bg-[var(--danger-bg)] text-[var(--danger-fg)] transition hover:scale-[1.03] disabled:cursor-not-allowed disabled:opacity-60"
            :disabled="isDeleting"
            @click="deleteLayout"
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
                :src="layout.image_url"
                :alt="layout.name"
                class="max-h-[80vh] w-full rounded-2xl border border-white/20 object-contain bg-black"
            >
        </div>
    </div>
</template>
