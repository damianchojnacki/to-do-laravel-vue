import '../css/app.css';

import App from '@/components/App.vue';
import { initializeTheme } from '@/composables/useAppearance';
import { VueQueryPlugin } from '@tanstack/vue-query';
import { createPinia } from 'pinia';
import { createApp } from 'vue';
import { ZiggyVue } from 'ziggy-js';

const pinia = createPinia();

createApp(App).use(VueQueryPlugin).use(ZiggyVue).use(pinia).mount('#app');

// This will set light / dark mode on page load...
initializeTheme();
