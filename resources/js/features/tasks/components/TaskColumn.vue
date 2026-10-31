<script setup lang="ts">
import { ref } from 'vue';
import { Plus } from 'lucide-vue-next';

import type { Task, TaskStatus } from '@/features/tasks/types';
import TaskCard from '@/features/tasks/components/TaskCard.vue';
import EmptyState from '@/Components/ui/EmptyState.vue';
import { useTaskStore } from '@/features/tasks/store';
import { useI18n } from '@/composables/useI18n';

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

const { t } = useI18n();
const taskStore = useTaskStore();
const isDragOver = ref(false);

function handleDragOver(e: DragEvent): void {
    if (e.dataTransfer) {
        e.dataTransfer.dropEffect = 'move';
    }
    isDragOver.value = true;
}

function handleDragLeave(e: DragEvent): void {
    isDragOver.value = false;
}

async function handleDrop(e: DragEvent, targetTaskId?: number): Promise<void> {
    isDragOver.value = false;
    const rawId = e.dataTransfer?.getData('text/plain');
    if (!rawId) return;
    const droppedId = parseInt(rawId, 10);
    if (isNaN(droppedId)) return;

    const currentIds = props.tasks.map((t) => t.id).filter((id) => id !== droppedId);
    if (targetTaskId !== undefined) {
        const targetIndex = currentIds.indexOf(targetTaskId);
        if (targetIndex !== -1) {
            currentIds.splice(targetIndex, 0, droppedId);
        } else {
            currentIds.push(droppedId);
        }
    } else {
        currentIds.push(droppedId);
    }

    await taskStore.reorder(currentIds, props.status);
}
</script>

<template>
    <div
        class="flex flex-col rounded-2xl border bg-surface-light-2 p-4 transition-all duration-200 dark:bg-surface-dark-2/50"
        :class="[
            isDragOver
                ? 'border-plum-500 bg-plum-50/50 ring-2 ring-plum-500/30 dark:border-plum-400 dark:bg-plum-950/30'
                : 'border-appborder-light dark:border-appborder-dark',
        ]"
        @dragover.prevent="handleDragOver"
        @dragleave.prevent="handleDragLeave"
        @drop.prevent="handleDrop"
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
                :title="title"
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
                @drop.stop.prevent="handleDrop($event, task.id)"
            />

            <!-- Empty column educational state -->
            <EmptyState
                v-if="tasks.length === 0"
                compact
                :title="`Aucune tâche ${title.toLowerCase()}`"
                :description="status === 'todo' ? 'Créez votre première tâche pour lancer votre sprint.' : 'Glissez-déposez une carte ici pour mettre à jour son statut.'"
                :action-label="status === 'todo' ? '+ Ajouter' : undefined"
                @action="emit('newTask', status)"
            >
                <template #icon>
                    <Plus class="h-4 w-4" />
                </template>
            </EmptyState>
        </div>
    </div>
</template>
