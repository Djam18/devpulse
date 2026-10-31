<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight, Laptop, Moon, Sun } from 'lucide-vue-next';

import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import WelcomeHero from '@/Pages/Welcome/Partials/WelcomeHero.vue';
import WelcomeFeatures from '@/Pages/Welcome/Partials/WelcomeFeatures.vue';
import WelcomeCta from '@/Pages/Welcome/Partials/WelcomeCta.vue';
import { useI18n } from '@/composables/useI18n';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    laravelVersion?: string;
    phpVersion?: string;
}>();

const { t } = useI18n();
const isDark = ref(false);

function toggleTheme(): void {
    isDark.value = !isDark.value;
    const theme = isDark.value ? 'dark' : 'light';
    document.documentElement.classList.toggle('dark', isDark.value);
    localStorage.setItem('theme', theme);
}

onMounted(() => {
    isDark.value = localStorage.getItem('theme') === 'dark';
    document.documentElement.classList.toggle('dark', isDark.value);
});
</script>

<template>
    <Head :title="t('welcome.title')" />

    <div class="min-h-screen bg-white font-sans text-gray-900 selection:bg-plum-500 selection:text-white dark:bg-appbg-dark dark:text-gray-100">
        <!-- Ambient decorative background glow -->
        <div class="pointer-events-none fixed inset-0 overflow-hidden">
            <div class="absolute -top-40 right-1/4 h-[500px] w-[500px] rounded-full bg-plum-500/10 blur-[120px] dark:bg-plum-600/10"></div>
            <div class="absolute top-1/3 -left-40 h-[450px] w-[450px] rounded-full bg-peach-500/10 blur-[120px] dark:bg-peach-600/10"></div>
        </div>

        <!-- Sticky Navigation Header -->
        <header class="sticky top-0 z-50 border-b border-appborder-light bg-white/90 backdrop-blur-md dark:border-appborder-dark dark:bg-surface-dark/90">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <!-- Brand Logo -->
                <Link href="/" class="flex items-center">
                    <ApplicationLogo with-text />
                </Link>

                <!-- Center Nav Links -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-gray-600 dark:text-gray-300">
                    <a href="#features" class="transition-colors hover:text-plum-700 dark:hover:text-plum-300">{{ t('welcome.nav.features') }}</a>
                    <a href="#workflow" class="transition-colors hover:text-plum-700 dark:hover:text-plum-300">{{ t('welcome.nav.workflow') }}</a>
                    <a href="#metrics" class="transition-colors hover:text-plum-700 dark:hover:text-plum-300">{{ t('welcome.nav.metrics') }}</a>
                    <a href="#architecture" class="transition-colors hover:text-plum-700 dark:hover:text-plum-300">{{ t('welcome.nav.architecture') }}</a>
                </nav>

                <!-- Actions / Auth Links & Theme Switcher -->
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        :title="isDark ? 'Passer au thème clair' : 'Passer au thème sombre doux'"
                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-appborder-light bg-white text-gray-500 shadow-xs transition-all hover:bg-surface-light-2 hover:text-plum-700 focus:outline-none focus:ring-2 focus:ring-plum-500 dark:border-appborder-dark dark:bg-surface-dark dark:text-gray-400 dark:hover:text-plum-300"
                        @click="toggleTheme"
                    >
                        <Sun v-if="isDark" class="h-4 w-4" />
                        <Moon v-else class="h-4 w-4" />
                    </button>

                    <template v-if="$page.props.auth?.user">
                        <Link
                            :href="route('dashboard')"
                            class="inline-flex items-center gap-2 rounded-xl bg-plum-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-plum-800 dark:bg-plum-400 dark:text-gray-950 dark:hover:bg-plum-300"
                        >
                            <Laptop class="h-4 w-4" />
                            <span>{{ t('welcome.nav.dashboard') }}</span>
                        </Link>
                    </template>

                    <template v-else>
                        <Link
                            v-if="canLogin"
                            :href="route('login')"
                            class="hidden sm:inline-flex rounded-xl px-3.5 py-2 text-sm font-semibold text-gray-700 transition-colors hover:bg-surface-light-2 hover:text-plum-700 dark:text-gray-300 dark:hover:bg-surface-dark dark:hover:text-plum-300"
                        >
                            {{ t('welcome.nav.login') }}
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="inline-flex items-center gap-2 rounded-xl bg-plum-700 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-plum-700/20 transition-all hover:bg-plum-800 hover:scale-[1.02] active:scale-[0.98] dark:bg-plum-400 dark:text-gray-950 dark:hover:bg-plum-300"
                        >
                            <span>{{ t('welcome.nav.register') }}</span>
                            <ArrowRight class="h-4 w-4" />
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <main class="relative z-10">
            <WelcomeHero />
            <WelcomeFeatures />
            <WelcomeCta />
        </main>

        <!-- Footer -->
        <footer class="border-t border-appborder-light bg-white py-10 dark:border-appborder-dark dark:bg-surface-dark">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center">
                    <ApplicationLogo with-text />
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ t('welcome.footer.copy') }}
                </p>
                <div class="flex items-center gap-4 text-xs font-medium text-gray-500 dark:text-gray-400">
                    <a href="#features" class="hover:text-plum-700 dark:hover:text-plum-300">{{ t('welcome.nav.features') }}</a>
                    <a href="/docs/api" target="_blank" class="hover:text-plum-700 dark:hover:text-plum-300">{{ t('welcome.nav.apiDocs') }}</a>
                    <Link :href="route('login')" class="hover:text-plum-700 dark:hover:text-plum-300">{{ t('welcome.nav.login') }}</Link>
                </div>
            </div>
        </footer>
    </div>
</template>
