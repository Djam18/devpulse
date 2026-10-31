<script setup lang="ts">
import { ListFilter, Search } from 'lucide-vue-next';

import type { TaskProject } from '@/features/tasks/types';
import { useTaskStore } from '@/features/tasks/store';
import { useI18n } from '@/composables/useI18n';

withDefaults(
    defineProps<{
        projects?: TaskProject[];
    }>(),
    {
        projects: () => [],
    },
);

const { t } = useI18n();
const taskStore = useTaskStore();
</script>

<template>
    <div
        class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 rounded-2xl border border-appborder-light bg-white p-4 shadow-xs dark:border-appborder-dark dark:bg-surface-dark"
    >
        <!-- Search Input -->
        <div class="relative flex-1">
            <Search class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
            <input
                v-model="taskStore.searchQuery"
                type="text"
                :placeholder="t('tasks.filter.searchPlaceholder')"
                class="w-full rounded-xl border border-appborder-light bg-surface-light-2 py-2.5 pl-10 pr-4 text-sm text-gray-900 placeholder-gray-400 focus:border-plum-600 focus:bg-white focus:outline-none focus:ring-1 focus:ring-plum-600 dark:border-appborder-dark dark:bg-surface-dark-2 dark:text-gray-100 dark:placeholder-gray-500"
            />
        </div>

        <!-- Project Filter Selector -->
        <div class="flex items-center gap-2">
            <div class="relative">
                <select
                    v-model="taskStore.selectedProjectId"
                    class="appearance-none rounded-xl border border-appborder-light bg-surface-light-2 py-2.5 pl-3.5 pr-8 text-sm font-medium text-gray-700 shadow-xs focus:border-plum-600 focus:bg-white focus:outline-none focus:ring-1 focus:ring-plum-600 dark:border-appborder-dark dark:bg-surface-dark-2 dark:text-gray-200"
                >
                    <option :value="null">{{ t('tasks.filter.allProjects') }}</option>
                    <option v-for="proj in projects" :key="proj.id" :value="proj.id">
                        {{ proj.name }}
                    </option>
                </select>
                <ListFilter class="pointer-events-none absolute right-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-gray-400" />
            </div>
        </div>
    </div>
</template>
