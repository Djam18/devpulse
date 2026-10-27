<script setup lang="ts">
import { Plus } from 'lucide-vue-next';

import type { Task, TaskStatus } from '@/features/tasks/types';
import TaskCard from '@/features/tasks/components/TaskCard.vue';

const props = defineProps<{
    status: TaskStatus;
    title: string;
    tasks: Task[];
    accentColor: string;
}>();

const emit = defineEmits<{
    (e: 'editTask', task: Task): void;
    (e: 'deleteTask', taskId: number): void;
    (e: 'newTask', status: TaskStatus): void;
}>();
</script>

<template>
    <div
        class="flex flex-col rounded-2xl border border-appborder-light bg-surface-light-2 p-4 dark:border-appborder-dark dark:bg-surface-dark-2/50"
    >
        <!-- Column Header -->
        <div class="mb-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span
                    class="h-2.5 w-2.5 rounded-full"
                    :class="accentColor"
                ></span>
                <h3 class="text-sm font-bold tracking-wide text-gray-800 dark:text-gray-200">
                    {{ title }}
                </h3>
                <span
                    class="rounded-full border border-appborder-light bg-white px-2 py-0.5 text-xs font-semibold text-gray-600 shadow-xs dark:border-appborder-dark dark:bg-surface-dark dark:text-gray-400"
                >
                    {{ tasks.length }}
                </span>
            </div>

            <!-- Add Task to this column button -->
            <button
                type="button"
                title="Add task to this column"
                class="rounded-lg p-1 text-gray-400 transition-colors hover:bg-white hover:text-plum-700 dark:hover:bg-surface-dark dark:hover:text-plum-300"
                @click="emit('newTask', status)"
            >
                <Plus class="h-4 w-4" />
            </button>
        </div>

        <!-- Task List -->
        <div class="flex flex-1 flex-col gap-3 overflow-y-auto min-h-[150px]">
            <TaskCard
                v-for="task in tasks"
                :key="task.id"
                :task="task"
                @edit="(t) => emit('editTask', t)"
                @delete="(id) => emit('deleteTask', id)"
            />

            <!-- Empty column placeholder -->
            <div
                v-if="tasks.length === 0"
                class="flex flex-1 flex-col items-center justify-center rounded-xl border border-dashed border-appborder-light py-8 text-center text-xs text-gray-400 dark:border-appborder-dark dark:text-gray-500"
            >
                <span>No tasks in {{ title.toLowerCase() }}</span>
            </div>
        </div>
    </div>
</template>
