<script setup lang="ts">
import { ref, watch } from 'vue';

import BaseButton from '@/Components/ui/BaseButton.vue';
import BaseModal from '@/Components/ui/BaseModal.vue';
import TaskFormFields from '@/features/tasks/components/TaskFormFields.vue';
import type { Task, TaskPriority, TaskProject, TaskStatus } from '@/features/tasks/types';
import { useTaskStore } from '@/features/tasks/store';

const props = withDefaults(
    defineProps<{
        show: boolean;
        task?: Task | null;
        initialStatus?: TaskStatus;
        projects?: TaskProject[];
    }>(),
    {
        task: null,
        initialStatus: 'todo',
        projects: () => [],
    },
);

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'saved', task: Task): void;
}>();

const taskStore = useTaskStore();
const isSubmitting = ref(false);
const errorMessage = ref<string | null>(null);

const form = ref({
    title: '',
    project_id: null as number | null,
    description: '',
    priority: 'medium' as TaskPriority,
    status: 'todo' as TaskStatus,
    due_date: '',
    estimated_pomodoros: 1,
});

watch(
    () => props.show,
    (isOpen) => {
        if (!isOpen) return;
        errorMessage.value = null;
        form.value = props.task
            ? {
                  title: props.task.title,
                  project_id: props.task.project_id,
                  description: props.task.description || '',
                  priority: props.task.priority,
                  status: props.task.status,
                  due_date: props.task.due_date || '',
                  estimated_pomodoros: props.task.estimated_pomodoros || 1,
              }
            : {
                  title: '',
                  project_id: props.projects.length > 0 ? props.projects[0].id : null,
                  description: '',
                  priority: 'medium',
                  status: props.initialStatus || 'todo',
                  due_date: '',
                  estimated_pomodoros: 1,
              };
    },
    { immediate: true },
);

async function handleSubmit(): Promise<void> {
    if (!form.value.title.trim()) {
        errorMessage.value = 'Task title is required.';
        return;
    }
    isSubmitting.value = true;
    errorMessage.value = null;

    try {
        const payload = {
            title: form.value.title.trim(),
            project_id: form.value.project_id,
            description: form.value.description.trim() || null,
            priority: form.value.priority,
            status: form.value.status,
            due_date: form.value.due_date || null,
            estimated_pomodoros: form.value.estimated_pomodoros,
        };
        const result = props.task
            ? await taskStore.update(props.task.id, payload)
            : await taskStore.create(payload);

        emit('saved', result);
        emit('close');
    } catch (err: any) {
        errorMessage.value = err?.response?.data?.message || 'Failed to save task.';
    } finally {
        isSubmitting.value = false;
    }
}
</script>

<template>
    <BaseModal :show="show" :title="task ? 'Edit Task' : 'Create New Task'" max-width="lg" @close="emit('close')">
        <div v-if="errorMessage" class="mb-4 rounded-lg bg-rose-50 p-2.5 text-xs font-semibold text-rose-600 dark:bg-rose-950/40 dark:text-rose-400">
            {{ errorMessage }}
        </div>

        <form class="space-y-4" @submit.prevent="handleSubmit">
            <TaskFormFields v-model="form" :projects="projects" />

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-appborder-light dark:border-appborder-dark">
                <BaseButton variant="ghost" size="sm" @click="emit('close')">Cancel</BaseButton>
                <BaseButton type="submit" variant="primary" size="sm" :loading="isSubmitting">
                    {{ task ? 'Update Task' : 'Create Task' }}
                </BaseButton>
            </div>
        </form>
    </BaseModal>
</template>
