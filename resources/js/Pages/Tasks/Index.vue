<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { CheckCircle2, Clock, ListFilter, Plus, Search, Sparkles } from 'lucide-vue-next';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TaskColumn from '@/features/tasks/components/TaskColumn.vue';
import TaskModal from '@/features/tasks/components/TaskModal.vue';
import { useTaskStore } from '@/features/tasks/store';
import type { Task, TaskProject, TaskStatus } from '@/features/tasks/types';

const props = withDefaults(
    defineProps<{
        projects?: TaskProject[];
    }>(),
    {
        projects: () => [],
    },
);

const taskStore = useTaskStore();

const isModalOpen = ref(false);
const editingTask = ref<Task | null>(null);
const targetInitialStatus = ref<TaskStatus>('todo');

onMounted(() => {
    taskStore.loadTasks();
});

const totalTasksCount = computed(() => taskStore.tasks.length);
const todoCount = computed(() => taskStore.tasks.filter((t) => t.status === 'todo').length);
const inProgressCount = computed(() => taskStore.tasks.filter((t) => t.status === 'in_progress').length);
const doneCount = computed(() => taskStore.tasks.filter((t) => t.status === 'done').length);

const completionPercentage = computed(() => {
    if (totalTasksCount.value === 0) return 0;
    return Math.round((doneCount.value / totalTasksCount.value) * 100);
});

function handleOpenCreateModal(status: TaskStatus = 'todo'): void {
    editingTask.value = null;
    targetInitialStatus.value = status;
    isModalOpen.value = true;
}

function handleOpenEditModal(task: Task): void {
    editingTask.value = task;
    targetInitialStatus.value = task.status;
    isModalOpen.value = true;
}

async function handleDeleteTask(taskId: number): Promise<void> {
    if (confirm('Are you sure you want to delete this task?')) {
        await taskStore.remove(taskId);
    }
}
</script>

<template>
    <Head title="Kanban Tasks" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-gray-100">
                            Tasks & Kanban
                        </h2>
                        <span class="rounded-full bg-plum-100 px-2.5 py-0.5 text-xs font-bold text-plum-800 dark:bg-plum-950/50 dark:text-plum-300">
                            Sprint Board
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Prioritize daily technical debt, feature milestones, and track live session times.
                    </p>
                </div>

                <!-- Action Button -->
                <button
                    type="button"
                    class="inline-flex items-center gap-2 rounded-xl bg-plum-700 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-plum-700/20 transition-all hover:bg-plum-800 hover:scale-[1.02] active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-plum-500 focus:ring-offset-2 dark:bg-plum-400 dark:text-gray-950 dark:hover:bg-plum-300 dark:focus:ring-offset-gray-900"
                    @click="handleOpenCreateModal('todo')"
                >
                    <Plus class="h-4 w-4" />
                    <span>New Task</span>
                </button>
            </div>
        </template>

        <div class="py-6 sm:py-8 bg-white dark:bg-appbg-dark">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">
                <!-- KPI Status Bar -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <!-- Total Tasks -->
                    <div class="rounded-2xl border border-appborder-light bg-white p-4 shadow-xs dark:border-appborder-dark dark:bg-surface-dark">
                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                            <span>Total Items</span>
                            <Sparkles class="h-4 w-4 text-plum-600 dark:text-plum-400" />
                        </div>
                        <p class="mt-2 text-2xl font-black text-gray-900 dark:text-gray-100">
                            {{ totalTasksCount }}
                        </p>
                    </div>

                    <!-- In Progress -->
                    <div class="rounded-2xl border border-appborder-light bg-white p-4 shadow-xs dark:border-appborder-dark dark:bg-surface-dark">
                        <div class="flex items-center justify-between text-xs text-peach-700 dark:text-peach-400">
                            <span>In Progress</span>
                            <Clock class="h-4 w-4" />
                        </div>
                        <p class="mt-2 text-2xl font-black text-gray-900 dark:text-gray-100">
                            {{ inProgressCount }}
                        </p>
                    </div>

                    <!-- Completed -->
                    <div class="rounded-2xl border border-appborder-light bg-white p-4 shadow-xs dark:border-appborder-dark dark:bg-surface-dark">
                        <div class="flex items-center justify-between text-xs text-emerald-600 dark:text-emerald-400">
                            <span>Done</span>
                            <CheckCircle2 class="h-4 w-4" />
                        </div>
                        <p class="mt-2 text-2xl font-black text-gray-900 dark:text-gray-100">
                            {{ doneCount }}
                        </p>
                    </div>

                    <!-- Progress percentage -->
                    <div class="rounded-2xl border border-appborder-light bg-white p-4 shadow-xs dark:border-appborder-dark dark:bg-surface-dark">
                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                            <span>Completion Rate</span>
                            <span class="font-bold text-plum-700 dark:text-plum-300">{{ completionPercentage }}%</span>
                        </div>
                        <!-- Progress bar -->
                        <div class="mt-4 h-2 w-full rounded-full bg-surface-light-2 dark:bg-surface-dark-2 overflow-hidden">
                            <div
                                class="h-full rounded-full bg-gradient-to-r from-plum-600 to-peach-500 transition-all duration-500"
                                :style="{ width: `${completionPercentage}%` }"
                            ></div>
                        </div>
                    </div>
                </div>

                <!-- Search & Filter Controls -->
                <div
                    class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 rounded-2xl border border-appborder-light bg-white p-4 shadow-xs dark:border-appborder-dark dark:bg-surface-dark"
                >
                    <!-- Search Input with Icon -->
                    <div class="relative flex-1">
                        <Search
                            class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                        />
                        <input
                            v-model="taskStore.searchQuery"
                            type="text"
                            placeholder="Search tasks by title or description..."
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
                                <option :value="null">All Projects</option>
                                <option
                                    v-for="proj in projects"
                                    :key="proj.id"
                                    :value="proj.id"
                                >
                                    {{ proj.name }}
                                </option>
                            </select>
                            <ListFilter class="pointer-events-none absolute right-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-gray-400" />
                        </div>
                    </div>
                </div>

                <!-- Kanban Columns Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                    <!-- Column: To Do -->
                    <TaskColumn
                        status="todo"
                        title="To Do"
                        accent-color="bg-slate-400"
                        :tasks="taskStore.todoTasks"
                        @edit-task="handleOpenEditModal"
                        @delete-task="handleDeleteTask"
                        @new-task="handleOpenCreateModal"
                    />

                    <!-- Column: In Progress -->
                    <TaskColumn
                        status="in_progress"
                        title="In Progress"
                        accent-color="bg-peach-600"
                        :tasks="taskStore.inProgressTasks"
                        @edit-task="handleOpenEditModal"
                        @delete-task="handleDeleteTask"
                        @new-task="handleOpenCreateModal"
                    />

                    <!-- Column: Done -->
                    <TaskColumn
                        status="done"
                        title="Done"
                        accent-color="bg-emerald-600"
                        :tasks="taskStore.doneTasks"
                        @edit-task="handleOpenEditModal"
                        @delete-task="handleDeleteTask"
                        @new-task="handleOpenCreateModal"
                    />
                </div>
            </div>
        </div>

        <!-- Task Modal (Create & Edit) -->
        <TaskModal
            :show="isModalOpen"
            :task="editingTask"
            :initial-status="targetInitialStatus"
            :projects="projects"
            @close="isModalOpen = false"
            @saved="isModalOpen = false"
        />
    </AuthenticatedLayout>
</template>
