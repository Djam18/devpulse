<script setup lang="ts">
import { computed, onMounted } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ArrowRight, CheckCircle2, Clock, KanbanSquare, Play, Sparkles, Timer } from 'lucide-vue-next';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useTaskStore } from '@/features/tasks/store';
import { useTimerStore } from '@/features/time-tracker/store';

const page = usePage();
const timerStore = useTimerStore();
const taskStore = useTaskStore();

const userName = computed(() => (page.props.auth as any)?.user?.name || 'Developer');

onMounted(() => {
    timerStore.loadActiveTimer();
    taskStore.loadTasks();
});

const todayDate = new Intl.DateTimeFormat('en-US', {
    weekday: 'long',
    month: 'short',
    day: 'numeric',
}).format(new Date());

const inProgressTasks = computed(() => taskStore.tasks.filter((t) => t.status === 'in_progress'));
const recentTasks = computed(() => taskStore.tasks.slice(0, 5));
</script>

<template>
    <Head title="Developer Hub" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-gray-100">
                        Developer Hub
                    </h2>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        {{ todayDate }} · Streamline deep work sprints and active client deliverables.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <Link
                        :href="route('tasks.index')"
                        class="inline-flex items-center gap-2 rounded-xl bg-plum-700 px-4 py-2 text-sm font-semibold text-white shadow-xs hover:bg-plum-800 transition-all dark:bg-plum-400 dark:text-gray-950 dark:hover:bg-plum-300"
                    >
                        <KanbanSquare class="h-4 w-4" />
                        <span>Go to Kanban</span>
                        <ArrowRight class="h-4 w-4" />
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-6 sm:py-8 bg-white dark:bg-appbg-dark">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">
                <!-- Welcome Banner -->
                <div
                    class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-plum-950 via-purple-900 to-slate-900 p-6 sm:p-8 text-white shadow-xl ring-1 ring-white/10"
                >
                    <div class="relative z-10 max-w-2xl space-y-2">
                        <div class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold backdrop-blur-md text-peach-300">
                            <Sparkles class="h-3.5 w-3.5" />
                            <span>Sprint Velocity Active</span>
                        </div>
                        <h3 class="text-2xl sm:text-3xl font-black tracking-tight">
                            Ready to ship, {{ userName }}?
                        </h3>
                        <p class="text-sm text-purple-200">
                            You have <strong class="text-white">{{ inProgressTasks.length }} active tasks</strong> in progress. Focus on one high-impact milestone at a time.
                        </p>
                    </div>

                    <!-- Ambient Glow -->
                    <div class="absolute -right-12 -top-12 h-64 w-64 rounded-full bg-peach-500/20 blur-3xl pointer-events-none"></div>
                </div>

                <!-- 3 Action Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Card 1: Active Time Tracker Status -->
                    <div
                        class="flex flex-col justify-between rounded-2xl border p-6 shadow-xs transition-all"
                        :class="[
                            timerStore.isRunning
                                ? 'border-peach-300/80 bg-peach-50/60 dark:border-peach-400/20 dark:bg-peach-950/20 ring-1 ring-peach-500/20'
                                : 'border-appborder-light bg-white dark:border-appborder-dark dark:bg-surface-dark',
                        ]"
                    >
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Live Tracker
                                </span>
                                <div class="flex items-center gap-1.5">
                                    <span
                                        class="h-2 w-2 rounded-full"
                                        :class="timerStore.isRunning ? 'bg-peach-500 animate-ping' : 'bg-gray-300 dark:bg-gray-600'"
                                    ></span>
                                    <span class="text-xs font-semibold" :class="timerStore.isRunning ? 'text-peach-700 dark:text-peach-400' : 'text-gray-500'">
                                        {{ timerStore.isRunning ? 'TRACKING' : 'IDLE' }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div v-if="timerStore.isRunning" class="space-y-1">
                                    <p class="text-3xl font-black font-mono text-peach-700 dark:text-peach-400">
                                        {{ timerStore.formattedElapsed }}
                                    </p>
                                    <p class="truncate text-xs font-medium text-gray-600 dark:text-gray-300">
                                        {{ timerStore.activeEntry?.description || 'Active coding session' }}
                                    </p>
                                </div>
                                <div v-else class="space-y-1">
                                    <p class="text-3xl font-black font-mono text-gray-400 dark:text-gray-600">
                                        00:00:00
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        No active session running. Start one from the top bar or a task card.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 border-t border-appborder-light dark:border-appborder-dark pt-4">
                            <button
                                v-if="timerStore.isRunning"
                                type="button"
                                class="w-full rounded-xl bg-peach-700 py-2 text-center text-xs font-bold uppercase tracking-wider text-white shadow-xs hover:bg-peach-800 transition-all dark:bg-peach-400 dark:text-gray-950"
                                @click="timerStore.stop()"
                            >
                                Stop Active Timer
                            </button>
                            <Link
                                v-else
                                :href="route('tasks.index')"
                                class="flex items-center justify-center gap-1.5 text-xs font-semibold text-plum-700 hover:text-plum-800 dark:text-plum-400"
                            >
                                <span>Pick task to track</span>
                                <ArrowRight class="h-3.5 w-3.5" />
                            </Link>
                        </div>
                    </div>

                    <!-- Card 2: Sprint Kanban Highlights -->
                    <div class="flex flex-col justify-between rounded-2xl border border-appborder-light bg-white p-6 shadow-xs dark:border-appborder-dark dark:bg-surface-dark">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Sprint Board
                                </span>
                                <KanbanSquare class="h-4 w-4 text-plum-600 dark:text-plum-400" />
                            </div>

                            <div class="mt-4 grid grid-cols-3 gap-2 text-center">
                                <div class="rounded-xl bg-surface-light-2 p-2.5 dark:bg-surface-dark-2">
                                    <span class="text-xs text-gray-400">To Do</span>
                                    <p class="mt-1 text-xl font-bold text-gray-800 dark:text-gray-200">
                                        {{ taskStore.todoTasks.length }}
                                    </p>
                                </div>
                                <div class="rounded-xl bg-peach-50 p-2.5 dark:bg-peach-950/30">
                                    <span class="text-xs text-peach-700 dark:text-peach-400">In Prog</span>
                                    <p class="mt-1 text-xl font-bold text-peach-700 dark:text-peach-300">
                                        {{ taskStore.inProgressTasks.length }}
                                    </p>
                                </div>
                                <div class="rounded-xl bg-emerald-50 p-2.5 dark:bg-emerald-950/30">
                                    <span class="text-xs text-emerald-600 dark:text-emerald-400">Done</span>
                                    <p class="mt-1 text-xl font-bold text-emerald-700 dark:text-emerald-300">
                                        {{ taskStore.doneTasks.length }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 border-t border-appborder-light dark:border-appborder-dark pt-4">
                            <Link
                                :href="route('tasks.index')"
                                class="flex items-center justify-center gap-1.5 text-xs font-semibold text-plum-700 hover:text-plum-800 dark:text-plum-400"
                            >
                                <span>Open Kanban columns</span>
                                <ArrowRight class="h-3.5 w-3.5" />
                            </Link>
                        </div>
                    </div>

                    <!-- Card 3: Deep Work Focus -->
                    <div class="flex flex-col justify-between rounded-2xl border border-appborder-light bg-white p-6 shadow-xs dark:border-appborder-dark dark:bg-surface-dark">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Deep Work
                                </span>
                                <Timer class="h-4 w-4 text-emerald-600 dark:text-emerald-400" />
                            </div>

                            <div class="mt-4 space-y-2">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-xs text-gray-500">Pomodoro Target</span>
                                    <span class="font-mono text-xs font-bold">25 min</span>
                                </div>
                                <p class="text-xs text-gray-500">
                                    Use single-task focus blocks to enter flow state with zero context switching.
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 border-t border-appborder-light dark:border-appborder-dark pt-4">
                            <div class="flex items-center justify-between text-xs text-gray-400">
                                <span>Status</span>
                                <span class="rounded-full bg-emerald-500/10 px-2 py-0.5 font-bold text-emerald-600 dark:text-emerald-400">
                                    Ready
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Tasks Quick Launch List -->
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
            </div>
        </div>
    </AuthenticatedLayout>
</template>
