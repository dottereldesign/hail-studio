<script setup>
import { ref } from 'vue';
import Navbar from '@/Components/Navbar.vue';
import MegaMenu from '@/Components/MegaMenu.vue';
import ToastContainer from '@/Components/ToastContainer.vue';

const megaMenuOpen = ref(false);

const toggleMegaMenu = () => {
    megaMenuOpen.value = !megaMenuOpen.value;
};

const closeMegaMenu = () => {
    megaMenuOpen.value = false;
};
</script>

<template>
    <div class="min-h-screen bg-slate-950 text-slate-100">
        <Navbar :is-open="megaMenuOpen" @toggle="toggleMegaMenu" />
        <MegaMenu :open="megaMenuOpen" @close="closeMegaMenu" />
        <main class="pt-16">
            <slot />
        </main>
        <ToastContainer />
    </div>
</template>
