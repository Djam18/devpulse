<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Play } from 'lucide-vue-next';

import { useTaskStore } from '@/features/tasks/store';
import { useTimerStore } from '@/features/time-tracker/store';

const taskStore = useTaskStore();
const timerStore = useTimerStore();

const recentTasks = computed(() => taskStore.tasks.slice(0, 5));
</script>

<template>
    <div class="rounded-2xl border border-appborder-light bg-white p-6 shadow-xs dark:border-appborder-dark dark:bg-surface-dark">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">
                    Recent Sprint Tasks
                </h4>
                <span class="rounded-full border border-appborder-light bg-surface-light-2 px-2 py-0.5 text-xs font-bold text-gray-600 dark:border-appborder-dark dark:bg-surface-dark-2 dark:text-gray-400">
                    Quick Action
                </span>
            </div>
            <Link
                :href="route('tasks.index')"
                class="text-xs font-semibold text-plum-700 hover:text-plum-800 dark:text-plum-400"
            >
                View All ({{ taskStore.tasks.length }})
            </Link>
        </div>

        <div class="divide-y divide-appborder-light dark:divide-appborder-dark">
            <div
                v-for="task in recentTasks"
                :key="task.id"
                class="flex items-center justify-between py-3 transition-colors hover:bg-surface-light-2/70 dark:hover:bg-surface-dark-2/40 rounded-xl px-2"
            >
                <div class="flex items-center gap-3 min-w-0">
                    <span
                        class="h-2 w-2 rounded-full shrink-0"
                        :class="[
                            task.status === 'done'
                                ? 'bg-emerald-500'
                                : task.status === 'in_progress'
                                  ? 'bg-peach-600'
                                  : 'bg-slate-300 dark:bg-gray-600',
                        ]"
                    ></span>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-gray-800 dark:text-gray-200">
                            {{ task.title }}
                        </p>
                        <div class="flex items-center gap-2 text-xs text-gray-400">
                            <span v-if="task.project" class="font-medium text-plum-700 dark:text-plum-400">
                                {{ task.project.name }}
                            </span>
                            <span>·</span>
                            <span class="uppercase text-[10px] font-bold">{{ task.priority }}</span>
                        </div>
                    </div>
                </div>

                <!-- Fast Timer Trigger -->
                <button
                    type="button"
                    title="Launch timer on this task"
                    class="flex items-center gap-1.5 rounded-xl border border-appborder-light px-3 py-1.5 text-xs font-semibold text-gray-700 shadow-xs transition-all hover:border-plum-500 hover:bg-plum-50 hover:text-plum-700 dark:border-appborder-dark dark:text-gray-300 dark:hover:border-plum-500 dark:hover:bg-plum-950/40 dark:hover:text-plum-300 shrink-0 ml-4"
                    @click="timerStore.start({ description: task.title, project_id: task.project_id ?? undefined, task_id: task.id })"
                >
                    <Play class="h-3 w-3 fill-current text-plum-700 dark:text-plum-400" />
                    <span>Track</span>
                </button>
            </div>
        </div>
    </div>
</template>
