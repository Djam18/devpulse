<script setup lang="ts">
import { computed, onMounted } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ArrowRight, KanbanSquare, Sparkles } from 'lucide-vue-next';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DashboardActionCards from '@/features/dashboard/components/DashboardActionCards.vue';
import ProjectDistributionCard from '@/features/dashboard/components/ProjectDistributionCard.vue';
import RecentTasksCard from '@/features/dashboard/components/RecentTasksCard.vue';
import WeeklyVelocityChart from '@/features/dashboard/components/WeeklyVelocityChart.vue';
import { useTaskStore } from '@/features/tasks/store';
import { useTimerStore } from '@/features/time-tracker/store';

withDefaults(
    defineProps<{
        metrics?: {
            totalWeekHours: number;
            weeklyGoalHours: number;
            estimatedRevenue: number;
            currency: string;
            completedPomodoros: number;
        };
        dailyActivity?: Array<{ day: string; date: string; hours: number }>;
        projectBreakdown?: Array<{ id: number | string | null; name: string; color: string; hours: number; percentage: number }>;
    }>(),
    {
        metrics: () => ({
            totalWeekHours: 0,
            weeklyGoalHours: 35,
            estimatedRevenue: 0,
            currency: 'EUR',
            completedPomodoros: 0,
        }),
        dailyActivity: () => [],
        projectBreakdown: () => [],
    },
);

const page = usePage();
const timerStore = useTimerStore();
const taskStore = useTaskStore();

const userName = computed(() => (page.props.auth as any)?.user?.name || 'Developer');
const inProgressTasks = computed(() => taskStore.tasks.filter((t) => t.status === 'in_progress'));

onMounted(() => {
    timerStore.loadActiveTimer();
    taskStore.loadTasks();
});

const todayDate = new Intl.DateTimeFormat('en-US', {
    weekday: 'long',
    month: 'short',
    day: 'numeric',
}).format(new Date());
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
                    class="relative overflow-hidden rounded-3xl bg-plum-950 p-6 sm:p-8 text-white shadow-xl ring-1 ring-plum-800"
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
                </div>

                <!-- 3 Action Cards Grid (Live Tracker, Sprint Board, Deep Work) -->
                <DashboardActionCards />

                <!-- Section: Weekly Pulse Velocity & Project Distribution -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <WeeklyVelocityChart
                        :daily-activity="dailyActivity"
                        :total-week-hours="metrics.totalWeekHours"
                        :weekly-goal-hours="metrics.weeklyGoalHours"
                    />

                    <ProjectDistributionCard
                        :project-breakdown="projectBreakdown"
                        :estimated-revenue="metrics.estimatedRevenue"
                        :currency="metrics.currency"
                    />
                </div>

                <!-- Recent Tasks Quick Launch List -->
                <RecentTasksCard />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
