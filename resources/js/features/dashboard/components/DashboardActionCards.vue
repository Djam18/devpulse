<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowRight, KanbanSquare, Timer } from 'lucide-vue-next';

import { BaseButton, BaseCard } from '@/Components/ui';
import MetricTooltip from '@/Components/ui/MetricTooltip.vue';
import { useTaskStore } from '@/features/tasks/store';
import { useTimerStore } from '@/features/time-tracker/store';

const timerStore = useTimerStore();
const taskStore = useTaskStore();
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card 1: Active Time Tracker Status -->
        <BaseCard
            class="flex flex-col justify-between"
            :class="timerStore.isRunning ? 'border-peach-300/80 bg-peach-50/60 dark:border-peach-400/20 dark:bg-peach-950/20 ring-1 ring-peach-500/20' : ''"
        >
            <div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Live Tracker</span>
                        <MetricTooltip text="Unicité stricte : démarrer un nouveau chrono clôture automatiquement le précédent." />
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="h-2 w-2 rounded-full" :class="timerStore.isRunning ? 'bg-peach-500 animate-ping' : 'bg-gray-300 dark:bg-gray-600'"></span>
                        <span class="text-xs font-semibold" :class="timerStore.isRunning ? 'text-peach-700 dark:text-peach-400' : 'text-gray-500'">
                            {{ timerStore.isRunning ? 'TRACKING' : 'IDLE' }}
                        </span>
                    </div>
                </div>

                <div class="mt-4">
                    <div v-if="timerStore.isRunning" class="space-y-1">
                        <p class="text-3xl font-black font-mono text-peach-700 dark:text-peach-400">{{ timerStore.formattedElapsed }}</p>
                        <p class="truncate text-xs font-medium text-gray-600 dark:text-gray-300">{{ timerStore.activeEntry?.description || 'Active coding session' }}</p>
                    </div>
                    <div v-else class="space-y-1">
                        <p class="text-3xl font-black font-mono text-gray-400 dark:text-gray-600">00:00:00</p>
                        <p class="text-xs text-gray-500">No active session running. Start one from the top bar or a task card.</p>
                    </div>
                </div>
            </div>

            <template #footer>
                <BaseButton v-if="timerStore.isRunning" variant="accent" class="w-full" @click="timerStore.stop()">
                    Stop Active Timer
                </BaseButton>
                <Link v-else :href="route('tasks.index')" class="flex items-center justify-center gap-1.5 text-xs font-semibold text-plum-700 hover:text-plum-800 dark:text-plum-400">
                    <span>Pick task to track</span>
                    <ArrowRight class="h-3.5 w-3.5" />
                </Link>
            </template>
        </BaseCard>

        <!-- Card 2: Sprint Kanban Highlights -->
        <BaseCard class="flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Sprint Board</span>
                    <KanbanSquare class="h-4 w-4 text-plum-600 dark:text-plum-400" />
                </div>

                <div class="mt-4 grid grid-cols-3 gap-2 text-center">
                    <div class="rounded-xl bg-surface-light-2 p-2.5 dark:bg-surface-dark-2">
                        <span class="text-xs text-gray-400">To Do</span>
                        <p class="mt-1 text-xl font-bold text-gray-800 dark:text-gray-200">{{ taskStore.todoTasks.length }}</p>
                    </div>
                    <div class="rounded-xl bg-peach-50 p-2.5 dark:bg-peach-950/30">
                        <span class="text-xs text-peach-700 dark:text-peach-400">In Prog</span>
                        <p class="mt-1 text-xl font-bold text-peach-700 dark:text-peach-300">{{ taskStore.inProgressTasks.length }}</p>
                    </div>
                    <div class="rounded-xl bg-emerald-50 p-2.5 dark:bg-emerald-950/30">
                        <span class="text-xs text-emerald-600 dark:text-emerald-400">Done</span>
                        <p class="mt-1 text-xl font-bold text-emerald-700 dark:text-emerald-300">{{ taskStore.doneTasks.length }}</p>
                    </div>
                </div>
            </div>

            <template #footer>
                <Link :href="route('tasks.index')" class="flex items-center justify-center gap-1.5 text-xs font-semibold text-plum-700 hover:text-plum-800 dark:text-plum-400">
                    <span>Open Kanban columns</span>
                    <ArrowRight class="h-3.5 w-3.5" />
                </Link>
            </template>
        </BaseCard>

        <!-- Card 3: Deep Work Focus -->
        <BaseCard class="flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Deep Work</span>
                        <MetricTooltip text="Blocs de 25 minutes de focus profond suivis de 5 minutes de pause pour prévenir la fatigue mentale." />
                    </div>
                    <Timer class="h-4 w-4 text-emerald-600 dark:text-emerald-400" />
                </div>

                <div class="mt-4 space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-xs text-gray-500">Pomodoro Target</span>
                        <span class="font-mono text-xs font-bold">25 min</span>
                    </div>
                    <p class="text-xs text-gray-500">Use single-task focus blocks to enter flow state with zero context switching.</p>
                </div>
            </div>

            <template #footer>
                <div class="flex items-center justify-between text-xs text-gray-400">
                    <span>Status</span>
                    <span class="rounded-full bg-emerald-500/10 px-2 py-0.5 font-bold text-emerald-600 dark:text-emerald-400">Ready</span>
                </div>
            </template>
        </BaseCard>
    </div>
</template>
