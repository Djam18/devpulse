<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';

import BaseButton from '@/Components/ui/BaseButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TaskColumn from '@/features/tasks/components/TaskColumn.vue';
import TaskFilterBar from '@/features/tasks/components/TaskFilterBar.vue';
import TaskKpiBar from '@/features/tasks/components/TaskKpiBar.vue';
import TaskModal from '@/features/tasks/components/TaskModal.vue';
import PomodoroWidget from '@/features/tasks/components/PomodoroWidget.vue';
import { useTaskStore } from '@/features/tasks/store';
import { useI18n } from '@/composables/useI18n';
import type { Task, TaskProject, TaskStatus } from '@/features/tasks/types';

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

const isModalOpen = ref(false);
const editingTask = ref<Task | null>(null);
const targetInitialStatus = ref<TaskStatus>('todo');

onMounted(() => {
    taskStore.loadTasks();
});

const totalTasksCount = computed(() => taskStore.tasks.length);
const inProgressCount = computed(() => taskStore.tasks.filter((t) => t.status === 'in_progress').length);
const doneCount = computed(() => taskStore.tasks.filter((t) => t.status === 'done').length);
const completionPercentage = computed(() => (totalTasksCount.value === 0 ? 0 : Math.round((doneCount.value / totalTasksCount.value) * 100)));

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
    if (confirm(t('tasks.deleteConfirm'))) {
        await taskStore.remove(taskId);
    }
}
</script>

<template>
    <Head :title="t('tasks.title')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-gray-100">
                            {{ t('tasks.header') }}
                        </h2>
                        <span class="rounded-full bg-plum-100 px-2.5 py-0.5 text-xs font-bold text-plum-800 dark:bg-plum-950/50 dark:text-plum-300">
                            {{ t('tasks.badge') }}
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        {{ t('tasks.subtitle') }}
                    </p>
                </div>

                <BaseButton variant="primary" size="md" @click="handleOpenCreateModal('todo')">
                    <Plus class="h-4 w-4" />
                    <span>{{ t('tasks.newTaskButton') }}</span>
                </BaseButton>
            </div>
        </template>

        <div class="py-6 sm:py-8 bg-white dark:bg-appbg-dark">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">
                <!-- KPI Status Bar -->
                <TaskKpiBar
                    :total-tasks-count="totalTasksCount"
                    :in-progress-count="inProgressCount"
                    :done-count="doneCount"
                    :completion-percentage="completionPercentage"
                />

                <!-- Search & Project Filter Bar -->
                <TaskFilterBar :projects="projects" />

                <!-- Pomodoro Deep Work Focus Bar -->
                <PomodoroWidget />

                <!-- Kanban Columns Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                    <TaskColumn
                        status="todo"
                        :title="t('tasks.columns.todo')"
                        accent-color="bg-slate-400"
                        :tasks="taskStore.todoTasks"
                        @edit-task="handleOpenEditModal"
                        @delete-task="handleDeleteTask"
                        @new-task="handleOpenCreateModal"
                    />

                    <TaskColumn
                        status="in_progress"
                        :title="t('tasks.columns.inProgress')"
                        accent-color="bg-peach-600"
                        :tasks="taskStore.inProgressTasks"
                        @edit-task="handleOpenEditModal"
                        @delete-task="handleDeleteTask"
                        @new-task="handleOpenCreateModal"
                    />

                    <TaskColumn
                        status="done"
                        :title="t('tasks.columns.done')"
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
