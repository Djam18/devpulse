<script setup lang="ts">
import { Coins, PieChart } from 'lucide-vue-next';

import { formatHumanDuration, formatLocalizedCurrency } from '@/lib/formatters';

withDefaults(
    defineProps<{
        projectBreakdown?: Array<{ id: number | string | null; name: string; color: string; hours: number; percentage: number }>;
        estimatedRevenue?: number;
        currency?: string;
    }>(),
    {
        projectBreakdown: () => [],
        estimatedRevenue: 0,
        currency: 'EUR',
    },
);
</script>

<template>
    <div class="rounded-2xl border border-appborder-light bg-white p-6 shadow-xs dark:border-appborder-dark dark:bg-surface-dark flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <PieChart class="h-4 w-4 text-peach-600 dark:text-peach-400" />
                    <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">
                        Project Distribution
                    </h4>
                </div>
            </div>

            <!-- Horizontal Multi-Color Share Bar -->
            <div v-if="projectBreakdown.length > 0" class="space-y-4">
                <div class="h-3 w-full flex rounded-full overflow-hidden bg-gray-100 dark:bg-gray-800">
                    <div
                        v-for="proj in projectBreakdown"
                        :key="proj.id ?? proj.name"
                        :style="{ width: `${proj.percentage}%`, backgroundColor: proj.color }"
                        :title="`${proj.name}: ${formatHumanDuration(proj.hours)} (${proj.percentage}%)`"
                        class="h-full first:rounded-l-full last:rounded-r-full transition-all duration-300 hover:opacity-90"
                    ></div>
                </div>

                <!-- Projects Legend List -->
                <div class="space-y-2 max-h-36 overflow-y-auto pr-1">
                    <div
                        v-for="proj in projectBreakdown"
                        :key="proj.id ?? proj.name"
                        class="flex items-center justify-between text-xs py-1"
                    >
                        <div class="flex items-center gap-2 truncate">
                            <span
                                class="h-2 w-2 rounded-full shrink-0"
                                :style="{ backgroundColor: proj.color }"
                            ></span>
                            <span class="truncate font-medium text-gray-800 dark:text-gray-200">
                                {{ proj.name }}
                            </span>
                        </div>
                        <span class="font-bold text-gray-700 dark:text-gray-300 shrink-0">
                            {{ formatHumanDuration(proj.hours) }} ({{ proj.percentage }}%)
                        </span>
                    </div>
                </div>
            </div>

            <!-- Empty project breakdown state -->
            <div v-else class="py-8 text-center text-xs text-gray-400 dark:text-gray-500">
                <span>No tracked projects recorded this week yet.</span>
            </div>
        </div>

        <!-- Financial Estimate KPI -->
        <div class="mt-6 pt-4 border-t border-appborder-light dark:border-appborder-dark bg-surface-light-2/50 dark:bg-surface-dark-2/40 rounded-xl p-3">
            <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                <div class="flex items-center gap-1.5 font-semibold text-gray-700 dark:text-gray-300">
                    <Coins class="h-3.5 w-3.5 text-amber-500" />
                    <span>Estimated Revenue</span>
                </div>
                <span class="font-bold text-xs text-gray-400">Client Rates</span>
            </div>
            <p class="text-2xl font-black text-gray-900 dark:text-gray-100">
                {{ formatLocalizedCurrency(estimatedRevenue, currency) }}
            </p>
        </div>
    </div>
</template>
