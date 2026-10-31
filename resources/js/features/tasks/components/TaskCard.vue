<script setup lang="ts">
import { Calendar, CheckCircle2, ChevronRight, Play, Trash2 } from 'lucide-vue-next';

import { BaseBadge } from '@/Components/ui';
import type { Task, TaskPriority, TaskStatus } from '@/features/tasks/types';
import { useTaskStore } from '@/features/tasks/store';
import { useTimerStore } from '@/features/time-tracker/store';

const props = defineProps<{ task: Task }>();
const emit = defineEmits<{ (e: 'edit', task: Task): void; (e: 'delete', taskId: number): void }>();

const taskStore = useTaskStore();
const timerStore = useTimerStore();

const priorityMap: Record<TaskPriority, 'rose' | 'plum' | 'slate' | 'slate'> = {
    urgent: 'rose',
    high: 'plum',
    medium: 'slate',
    low: 'slate',
};

function handleDragStart(e: DragEvent): void {
    if (e.dataTransfer) {
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', String(props.task.id));
    }
}
</script>

<template>
    <div
        draggable="true"
        class="group relative rounded-xl border border-appborder-light bg-white p-4 shadow-xs transition-all duration-200 hover:border-plum-300 hover:shadow-md dark:border-appborder-dark dark:bg-surface-dark dark:hover:border-plum-800 cursor-grab active:cursor-grabbing select-none"
        @dragstart="handleDragStart"
    >
        <!-- Top bar: Priority & Project Tag -->
        <div class="flex items-center justify-between gap-2">
            <div class="flex items-center gap-1.5 flex-wrap">
                <BaseBadge :variant="priorityMap[task.priority] || 'slate'">{{ task.priority }}</BaseBadge>
                <span
                    v-if="task.project"
                    class="inline-flex items-center gap-1 rounded-md border border-appborder-light bg-surface-light-2 px-2 py-0.5 text-xs font-medium text-gray-700 dark:border-appborder-dark dark:bg-surface-dark-2 dark:text-gray-300"
                >
                    <span class="h-1.5 w-1.5 rounded-full" :style="{ backgroundColor: task.project.color }"></span>
                    <span class="truncate max-w-[120px]">{{ task.project.name }}</span>
                </span>
            </div>

            <div class="flex items-center gap-1 opacity-80 group-hover:opacity-100 transition-opacity">
                <button
                    type="button"
                    title="Track time"
                    class="rounded-md p-1 text-gray-400 hover:bg-plum-50 hover:text-plum-700 dark:hover:bg-plum-950/40 dark:hover:text-plum-300"
                    @click="timerStore.start({ description: task.title, project_id: task.project_id ?? undefined, task_id: task.id })"
                >
                    <Play class="h-3.5 w-3.5 fill-current" />
                </button>
                <button
                    type="button"
                    title="Delete task"
                    class="rounded-md p-1 text-gray-400 hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-950/40 dark:hover:text-rose-400"
                    @click="emit('delete', task.id)"
                >
                    <Trash2 class="h-3.5 w-3.5" />
                </button>
            </div>
        </div>

        <!-- Task title & description -->
        <div class="mt-2.5">
            <h4
                class="text-sm font-semibold text-gray-900 cursor-pointer hover:text-plum-700 dark:text-gray-100 dark:hover:text-plum-400 transition-colors"
                @click="emit('edit', task)"
            >
                {{ task.title }}
            </h4>
            <p v-if="task.description" class="mt-1 line-clamp-2 text-xs text-gray-500 dark:text-gray-400">
                {{ task.description }}
            </p>
        </div>

        <!-- Bottom bar -->
        <div class="mt-3.5 flex items-center justify-between border-t border-appborder-light pt-2.5 text-xs text-gray-500 dark:border-appborder-dark dark:text-gray-400">
            <div class="flex items-center gap-3">
                <div v-if="task.due_date" class="flex items-center gap-1">
                    <Calendar class="h-3.5 w-3.5 text-gray-400" />
                    <span>{{ task.due_date }}</span>
                </div>
                <div class="flex items-center gap-1 font-mono text-[11px]">
                    <span>🍅</span>
                    <span>{{ task.completed_pomodoros }}/{{ task.estimated_pomodoros }}</span>
                </div>
            </div>

            <div class="flex items-center gap-1">
                <button
                    v-if="task.status === 'todo'"
                    type="button"
                    class="flex items-center gap-0.5 rounded px-1.5 py-0.5 text-[11px] font-semibold text-plum-700 hover:bg-plum-50 dark:text-plum-400"
                    @click="taskStore.changeStatus(task.id, 'in_progress')"
                >
                    <span>Start</span>
                    <ChevronRight class="h-3 w-3" />
                </button>
                <button
                    v-else-if="task.status === 'in_progress'"
                    type="button"
                    class="flex items-center gap-0.5 rounded px-1.5 py-0.5 text-[11px] font-semibold text-peach-700 hover:bg-peach-50 dark:text-peach-400"
                    @click="taskStore.changeStatus(task.id, 'done')"
                >
                    <CheckCircle2 class="h-3 w-3" />
                    <span>Done</span>
                </button>
                <button
                    v-else-if="task.status === 'done'"
                    type="button"
                    class="rounded px-1.5 py-0.5 text-[11px] font-medium text-gray-400 hover:bg-surface-light-2"
                    @click="taskStore.changeStatus(task.id, 'todo')"
                >
                    Reopen
                </button>
            </div>
        </div>
    </div>
</template>
