<template>
    <Button variant="outline" class="absolute right-0 top-0 m-5 flex" @click="toggleTheme">
        <MoonIcon class="h-[1.2rem] w-[1.2rem] rotate-0 scale-100 transition-all dark:-rotate-90 dark:scale-0" />
        <SunIcon class="absolute h-[1.2rem] w-[1.2rem] rotate-90 scale-0 transition-all dark:rotate-0 dark:scale-100" />
        <span class="sr-only">Toggle theme</span>
    </Button>
</template>

<script setup lang="ts">
import { useAppearance } from '@/composables/useAppearance';
import { MoonIcon, SunIcon } from 'lucide-vue-next';

const { updateAppearance } = useAppearance();

const toggleTheme = () => {
    const isDark = document.documentElement.classList.contains('dark');

    updateAppearance(isDark ? 'light' : 'dark');
};
</script>
