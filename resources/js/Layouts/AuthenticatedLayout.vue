<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Briefcase, KanbanSquare, LayoutDashboard, Moon, Sun } from 'lucide-vue-next';

import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import ActiveTimerBar from '@/features/time-tracker/components/ActiveTimerBar.vue';
import UserDropdown from '@/Layouts/Partials/UserDropdown.vue';
import MobileNavDrawer from '@/Layouts/Partials/MobileNavDrawer.vue';

const showingNavigationDropdown = ref(false);
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

const navItemClass = (isActive: boolean) => [
    'flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all',
    isActive
        ? 'bg-plum-50 text-plum-700 shadow-xs dark:bg-plum-950/40 dark:text-plum-300'
        : 'text-gray-600 hover:bg-surface-light-2 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-surface-dark-2 dark:hover:text-gray-200',
];
</script>

<template>
    <div class="min-h-screen bg-white font-sans text-gray-900 antialiased selection:bg-plum-500 selection:text-white dark:bg-appbg-dark dark:text-gray-100">
        <!-- Sticky Navigation Bar -->
        <nav class="sticky top-0 z-40 border-b border-appborder-light bg-white/95 backdrop-blur-md dark:border-appborder-dark dark:bg-surface-dark/95">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between gap-4">
                    <!-- Brand Logo & Navigation Links -->
                    <div class="flex items-center gap-8">
                        <Link :href="route('dashboard')" class="flex shrink-0 items-center">
                            <ApplicationLogo with-text />
                        </Link>

                        <!-- Desktop Navigation Pills -->
                        <div class="hidden md:flex items-center gap-1.5">
                            <Link :href="route('dashboard')" :class="navItemClass(route().current('dashboard'))">
                                <LayoutDashboard class="h-4 w-4" />
                                <span>Dashboard</span>
                            </Link>

                            <Link :href="route('tasks.index')" :class="navItemClass(route().current('tasks.*'))">
                                <KanbanSquare class="h-4 w-4" />
                                <span>Tasks & Kanban</span>
                            </Link>

                            <Link :href="route('projects.index')" :class="navItemClass(route().current('projects.*'))">
                                <Briefcase class="h-4 w-4" />
                                <span>Projects</span>
                            </Link>
                        </div>
                    </div>

                    <!-- Persistent Active Timer Bar (Desktop Center) -->
                    <div class="hidden sm:flex flex-1 max-w-lg items-center justify-center px-2">
                        <ActiveTimerBar class="w-full" />
                    </div>

                    <!-- User Profile Dropdown & Controls -->
                    <div class="hidden sm:flex sm:items-center sm:gap-2.5">
                        <button
                            type="button"
                            :title="isDark ? 'Switch to Light Theme' : 'Switch to Soft Dark Theme'"
                            class="flex h-9 w-9 items-center justify-center rounded-xl border border-appborder-light bg-white text-gray-500 shadow-xs transition-all hover:bg-surface-light-2 hover:text-plum-700 focus:outline-none focus:ring-2 focus:ring-plum-500 dark:border-appborder-dark dark:bg-surface-dark-2 dark:text-gray-400 dark:hover:text-plum-300"
                            @click="toggleTheme"
                        >
                            <Sun v-if="isDark" class="h-4 w-4" />
                            <Moon v-else class="h-4 w-4" />
                        </button>

                        <UserDropdown />
                    </div>

                    <!-- Mobile Hamburger & Theme Toggle -->
                    <div class="-me-2 flex items-center gap-2 sm:hidden">
                        <button
                            type="button"
                            class="flex h-9 w-9 items-center justify-center rounded-xl border border-appborder-light text-gray-500 hover:bg-surface-light-2 dark:border-appborder-dark dark:text-gray-400"
                            @click="toggleTheme"
                        >
                            <Sun v-if="isDark" class="h-4 w-4" />
                            <Moon v-else class="h-4 w-4" />
                        </button>

                        <button
                            type="button"
                            class="inline-flex items-center justify-center rounded-xl p-2 text-gray-500 hover:bg-surface-light-2 dark:text-gray-400 dark:hover:bg-surface-dark-2"
                            @click="showingNavigationDropdown = !showingNavigationDropdown"
                        >
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path
                                    :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <MobileNavDrawer v-if="showingNavigationDropdown" />
        </nav>

        <!-- Page Sub-Header slot -->
        <header v-if="$slots.header" class="border-b border-appborder-light/80 bg-surface-light-2/60 backdrop-blur-sm dark:border-appborder-dark/80 dark:bg-surface-dark/40">
            <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <slot />
        </main>
    </div>
</template>
