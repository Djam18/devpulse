<script setup lang="ts">
import { CheckCircle2, Clock, Sparkles } from 'lucide-vue-next';
import { useI18n } from '@/composables/useI18n';

defineProps<{
    totalTasksCount: number;
    inProgressCount: number;
    doneCount: number;
    completionPercentage: number;
}>();

const { t } = useI18n();
</script>

<template>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <!-- Total Tasks -->
        <div class="rounded-2xl border border-appborder-light bg-white p-4 shadow-xs dark:border-appborder-dark dark:bg-surface-dark">
            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                <span>{{ t('tasks.kpi.total') }}</span>
                <Sparkles class="h-4 w-4 text-plum-600 dark:text-plum-400" />
            </div>
            <p class="mt-2 text-2xl font-black text-gray-900 dark:text-gray-100">
                {{ totalTasksCount }}
            </p>
        </div>

        <!-- In Progress -->
        <div class="rounded-2xl border border-appborder-light bg-white p-4 shadow-xs dark:border-appborder-dark dark:bg-surface-dark">
            <div class="flex items-center justify-between text-xs text-peach-700 dark:text-peach-400">
                <span>{{ t('tasks.kpi.inProgress') }}</span>
                <Clock class="h-4 w-4" />
            </div>
            <p class="mt-2 text-2xl font-black text-gray-900 dark:text-gray-100">
                {{ inProgressCount }}
            </p>
        </div>

        <!-- Completed -->
        <div class="rounded-2xl border border-appborder-light bg-white p-4 shadow-xs dark:border-appborder-dark dark:bg-surface-dark">
            <div class="flex items-center justify-between text-xs text-emerald-600 dark:text-emerald-400">
                <span>{{ t('tasks.kpi.done') }}</span>
                <CheckCircle2 class="h-4 w-4" />
            </div>
            <p class="mt-2 text-2xl font-black text-gray-900 dark:text-gray-100">
                {{ doneCount }}
            </p>
        </div>

        <!-- Progress percentage -->
        <div class="rounded-2xl border border-appborder-light bg-white p-4 shadow-xs dark:border-appborder-dark dark:bg-surface-dark">
            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                <span>{{ t('tasks.kpi.completionRate') }}</span>
                <span class="font-bold text-plum-700 dark:text-plum-300">{{ completionPercentage }}%</span>
            </div>
            <div class="mt-4 h-2 w-full rounded-full bg-surface-light-2 dark:bg-surface-dark-2 overflow-hidden">
                <div
                    class="h-full rounded-full bg-plum-600 dark:bg-plum-500 transition-all duration-500"
                    :style="{ width: `${completionPercentage}%` }"
                ></div>
            </div>
        </div>
    </div>
</template>
