<script setup>
import { onMounted, ref } from 'vue';
import Navbar from '@/Components/Navbar.vue';
import MegaMenu from '@/Components/MegaMenu.vue';
import ToastContainer from '@/Components/ToastContainer.vue';

defineProps({
    pageClass: {
        type: String,
        default: '',
    },
});

const megaMenuOpen = ref(false);
const theme = ref('light');

const toggleMegaMenu = () => {
    megaMenuOpen.value = !megaMenuOpen.value;
};

const closeMegaMenu = () => {
    megaMenuOpen.value = false;
};

const applyTheme = (value) => {
    theme.value = value;
    document.documentElement.dataset.theme = value;
    localStorage.setItem('theme', value);
};

const toggleTheme = () => {
    applyTheme(theme.value === 'dark' ? 'light' : 'dark');
};

onMounted(() => {
    const stored = localStorage.getItem('theme');
    applyTheme(stored ?? 'light');
});
</script>

<template>
    <div
        class="min-h-screen bg-[var(--color-bg)] text-[var(--color-text)]"
        :class="pageClass"
    >
        <Navbar :is-open="megaMenuOpen" @toggle="toggleMegaMenu" />
        <MegaMenu
            :open="megaMenuOpen"
            :theme="theme"
            @close="closeMegaMenu"
            @toggle-theme="toggleTheme"
        />
        <main class="pt-16">
            <slot />
        </main>
        <ToastContainer />
    </div>
</template>
