<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Briefcase, KanbanSquare, LayoutDashboard } from 'lucide-vue-next';
import ActiveTimerBar from '@/features/time-tracker/components/ActiveTimerBar.vue';
</script>

<template>
    <div class="sm:hidden border-b border-appborder-light bg-white p-4 dark:border-appborder-dark dark:bg-surface-dark space-y-3">
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
            <Link
                :href="route('projects.index')"
                class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-sm font-semibold"
                :class="[
                    route().current('projects.*')
                        ? 'bg-plum-50 text-plum-700 dark:bg-plum-950/40 dark:text-plum-300'
                        : 'text-gray-600 dark:text-gray-300',
                ]"
            >
                <Briefcase class="h-4 w-4" />
                <span>Projects</span>
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
</template>
