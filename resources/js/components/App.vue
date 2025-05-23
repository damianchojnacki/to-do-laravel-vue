<template>
    <Toaster />

    <div>
        <ThemeSwitch />

        <List />
    </div>
</template>

<script setup lang="ts">
import List from '@/components/Task/List.vue';
import ThemeSwitch from '@/components/ThemeSwitch.vue';
import Toaster from '@/components/ui/toast/Toaster.vue';
</script>
