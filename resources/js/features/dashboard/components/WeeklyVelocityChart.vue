<script setup lang="ts">
import { computed } from 'vue';
import { BarChart3 } from 'lucide-vue-next';

import { formatHumanDuration } from '@/lib/formatters';

const props = withDefaults(
    defineProps<{
        dailyActivity?: Array<{ day: string; date: string; hours: number }>;
        totalWeekHours?: number;
        weeklyGoalHours?: number;
    }>(),
    {
        dailyActivity: () => [],
        totalWeekHours: 0,
        weeklyGoalHours: 35,
    },
);

const maxDailyHours = computed(() => {
    const hours = props.dailyActivity.map((d) => d.hours);
    return Math.max(...hours, 8); // At least 8h scale
});

const goalProgressPercentage = computed(() => {
    if (!props.weeklyGoalHours) return 0;
    return Math.min(100, Math.round((props.totalWeekHours / props.weeklyGoalHours) * 100));
});
</script>

<template>
    <div class="lg:col-span-2 rounded-2xl border border-appborder-light bg-white p-6 shadow-xs dark:border-appborder-dark dark:bg-surface-dark">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2">
                <BarChart3 class="h-4 w-4 text-plum-600 dark:text-plum-400" />
                <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">
                    7-Day Rolling Velocity
                </h4>
            </div>
            <div class="flex items-center gap-3 text-xs">
                <span class="font-bold text-plum-700 dark:text-plum-300">
                    {{ formatHumanDuration(totalWeekHours) }} this week
                </span>
                <span class="text-gray-400">·</span>
                <span class="text-gray-500">Goal: {{ formatHumanDuration(weeklyGoalHours) }}</span>
            </div>
        </div>

        <!-- 7-Day Visual Bars -->
        <div class="grid grid-cols-7 gap-2 sm:gap-4 items-end h-40 pt-4 border-b border-appborder-light dark:border-appborder-dark pb-2">
            <div
                v-for="(day, index) in dailyActivity"
                :key="index"
                class="flex flex-col items-center gap-2 h-full justify-end group"
            >
                <span class="text-[10px] font-bold text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    {{ formatHumanDuration(day.hours) }}
                </span>
                <div class="w-full bg-surface-light-2 rounded-lg h-28 relative flex items-end overflow-hidden dark:bg-surface-dark-2">
                    <div
                        class="w-full rounded-lg transition-all duration-500 group-hover:brightness-110"
                        :class="day.hours > 0 ? 'bg-plum-700 dark:bg-plum-500' : 'bg-transparent'"
                        :style="{ height: `${Math.min(100, Math.max(day.hours > 0 ? 8 : 0, (day.hours / maxDailyHours) * 100))}%` }"
                    ></div>
                </div>
                <span class="text-xs font-semibold text-gray-600 dark:text-gray-300">
                    {{ day.day }}
                </span>
            </div>
        </div>

        <!-- Weekly Goal Progress Bar -->
        <div class="mt-4 flex items-center justify-between gap-4 text-xs">
            <div class="flex-1">
                <div class="flex items-center justify-between mb-1 font-semibold text-gray-600 dark:text-gray-300">
                    <span>Weekly Progress ({{ goalProgressPercentage }}%)</span>
                    <span>{{ formatHumanDuration(totalWeekHours) }} / {{ formatHumanDuration(weeklyGoalHours) }}</span>
                </div>
                <div class="h-2 w-full bg-surface-light-2 rounded-full overflow-hidden dark:bg-surface-dark-2">
                    <div
                        class="h-full bg-plum-600 transition-all duration-500 rounded-full"
                        :style="{ width: `${goalProgressPercentage}%` }"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</template>
