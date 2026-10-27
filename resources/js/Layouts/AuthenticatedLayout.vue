<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { ChevronDown, KanbanSquare, LayoutDashboard, LogOut, Moon, Sun, User } from 'lucide-vue-next';

import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ActiveTimerBar from '@/features/time-tracker/components/ActiveTimerBar.vue';

const page = usePage();
const showingNavigationDropdown = ref(false);
const isDark = ref(false);

function toggleTheme(): void {
    isDark.value = !isDark.value;
    if (isDark.value) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
}

onMounted(() => {
    const savedTheme = localStorage.getItem('theme');
    // Default to light mode (pure white background)
    if (savedTheme === 'dark') {
        isDark.value = true;
        document.documentElement.classList.add('dark');
    } else {
        isDark.value = false;
        document.documentElement.classList.remove('dark');
    }
});

const userName = computed(() => (page.props.auth as any)?.user?.name || 'User');
const userAvatar = computed(() => (page.props.auth as any)?.user?.avatar || null);
const userInitials = computed(() => {
    const parts = userName.value.split(' ');
    if (parts.length >= 2) {
        return `${parts[0][0]}${parts[1][0]}`.toUpperCase();
    }
    return userName.value.substring(0, 2).toUpperCase();
});
</script>

<template>
    <div class="min-h-screen bg-white font-sans text-gray-900 antialiased selection:bg-plum-500 selection:text-white dark:bg-appbg-dark dark:text-gray-100">
        <!-- Sticky Navigation Bar -->
        <nav
            class="sticky top-0 z-40 border-b border-appborder-light bg-white/95 backdrop-blur-md dark:border-appborder-dark dark:bg-surface-dark/95"
        >
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between gap-4">
                    <!-- Brand Logo & Navigation Links -->
                    <div class="flex items-center gap-8">
                        <Link :href="route('dashboard')" class="flex shrink-0 items-center">
                            <ApplicationLogo with-text />
                        </Link>

                        <!-- Desktop Navigation Pills -->
                        <div class="hidden md:flex items-center gap-1.5">
                            <Link
                                :href="route('dashboard')"
                                class="flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all"
                                :class="[
                                    route().current('dashboard')
                                        ? 'bg-plum-50 text-plum-700 shadow-xs dark:bg-plum-950/40 dark:text-plum-300'
                                        : 'text-gray-600 hover:bg-surface-light-2 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-surface-dark-2 dark:hover:text-gray-200',
                                ]"
                            >
                                <LayoutDashboard class="h-4 w-4" />
                                <span>Dashboard</span>
                            </Link>

                            <Link
                                :href="route('tasks.index')"
                                class="flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition-all"
                                :class="[
                                    route().current('tasks.*')
                                        ? 'bg-plum-50 text-plum-700 shadow-xs dark:bg-plum-950/40 dark:text-plum-300'
                                        : 'text-gray-600 hover:bg-surface-light-2 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-surface-dark-2 dark:hover:text-gray-200',
                                ]"
                            >
                                <KanbanSquare class="h-4 w-4" />
                                <span>Tasks & Kanban</span>
                            </Link>
                        </div>
                    </div>

                    <!-- Persistent Active Timer Bar (Desktop Center) -->
                    <div class="hidden sm:flex flex-1 max-w-lg items-center justify-center px-2">
                        <ActiveTimerBar class="w-full" />
                    </div>

                    <!-- User Profile Dropdown & Controls -->
                    <div class="hidden sm:flex sm:items-center sm:gap-2.5">
                        <!-- Theme Toggle Button -->
                        <button
                            type="button"
                            :title="isDark ? 'Switch to Light Theme' : 'Switch to Soft Dark Theme'"
                            class="flex h-9 w-9 items-center justify-center rounded-xl border border-appborder-light bg-white text-gray-500 shadow-xs transition-all hover:bg-surface-light-2 hover:text-plum-700 focus:outline-none focus:ring-2 focus:ring-plum-500 dark:border-appborder-dark dark:bg-surface-dark-2 dark:text-gray-400 dark:hover:text-plum-300"
                            @click="toggleTheme"
                        >
                            <Sun v-if="isDark" class="h-4 w-4" />
                            <Moon v-else class="h-4 w-4" />
                        </button>

                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button
                                    type="button"
                                    class="flex items-center gap-2.5 rounded-xl border border-appborder-light bg-white px-3 py-1.5 text-sm font-medium text-gray-700 shadow-xs transition-all hover:bg-surface-light-2 focus:outline-none focus:ring-2 focus:ring-plum-500 dark:border-appborder-dark dark:bg-surface-dark dark:text-gray-300 dark:hover:bg-surface-dark-2"
                                >
                                    <!-- User Avatar (Image or Initials) -->
                                    <img
                                        v-if="userAvatar"
                                        :src="userAvatar"
                                        alt="Avatar"
                                        class="h-7 w-7 rounded-lg object-cover ring-1 ring-appborder-light dark:ring-appborder-dark"
                                    />
                                    <div
                                        v-else
                                        class="flex h-7 w-7 items-center justify-center rounded-lg bg-gradient-to-tr from-plum-700 to-peach-600 text-xs font-bold text-white shadow-xs"
                                    >
                                        {{ userInitials }}
                                    </div>
                                    <span class="font-semibold">{{ userName }}</span>
                                    <ChevronDown class="h-3.5 w-3.5 text-gray-400" />
                                </button>
                            </template>

                            <template #content>
                                <div class="px-4 py-2 border-b border-appborder-light dark:border-appborder-dark">
                                    <p class="text-xs text-gray-400">Signed in as</p>
                                    <p class="text-xs font-bold text-gray-800 dark:text-gray-200 truncate">
                                        {{ (page.props.auth as any)?.user?.email }}
                                    </p>
                                </div>
                                <DropdownLink :href="route('profile.edit')" class="flex items-center gap-2">
                                    <User class="h-4 w-4 text-gray-400" />
                                    <span>Profile</span>
                                </DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button" class="flex items-center gap-2 text-rose-600 dark:text-rose-400">
                                    <LogOut class="h-4 w-4" />
                                    <span>Log Out</span>
                                </DropdownLink>
                            </template>
                        </Dropdown>
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
                                    :class="{
                                        hidden: showingNavigationDropdown,
                                        'inline-flex': !showingNavigationDropdown,
                                    }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    :class="{
                                        hidden: !showingNavigationDropdown,
                                        'inline-flex': showingNavigationDropdown,
                                    }"
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

            <!-- Mobile Navigation Drawer -->
            <div
                v-if="showingNavigationDropdown"
                class="sm:hidden border-b border-appborder-light bg-white p-4 dark:border-appborder-dark dark:bg-surface-dark space-y-3"
            >
                <!-- Mobile Timer Bar -->
                <div>
                    <ActiveTimerBar class="w-full" />
                </div>

                <div class="space-y-1">
                    <Link
                        :href="route('dashboard')"
                        class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-sm font-semibold"
                        :class="[
                            route().current('dashboard')
                                ? 'bg-plum-50 text-plum-700 dark:bg-plum-950/40 dark:text-plum-300'
                                : 'text-gray-600 dark:text-gray-300',
                        ]"
                    >
                        <LayoutDashboard class="h-4 w-4" />
                        <span>Dashboard</span>
                    </Link>
                    <Link
                        :href="route('tasks.index')"
                        class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-sm font-semibold"
                        :class="[
                            route().current('tasks.*')
                                ? 'bg-plum-50 text-plum-700 dark:bg-plum-950/40 dark:text-plum-300'
                                : 'text-gray-600 dark:text-gray-300',
                        ]"
                    >
                        <KanbanSquare class="h-4 w-4" />
                        <span>Tasks & Kanban</span>
                    </Link>
                </div>

                <div class="border-t border-appborder-light pt-3 dark:border-appborder-dark space-y-1">
                    <Link
                        :href="route('profile.edit')"
                        class="block rounded-xl px-3 py-2 text-sm text-gray-600 dark:text-gray-300"
                    >
                        Profile Settings
                    </Link>
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="block w-full text-left rounded-xl px-3 py-2 text-sm font-medium text-rose-600 dark:text-rose-400"
                    >
                        Log Out
                    </Link>
                </div>
            </div>
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
