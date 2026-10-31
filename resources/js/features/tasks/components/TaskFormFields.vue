<script setup lang="ts">
import { computed } from 'vue';
import type { TaskPriority, TaskProject, TaskStatus } from '@/features/tasks/types';

const props = defineProps<{
    modelValue: {
        title: string;
        project_id: number | null;
        description: string;
        priority: TaskPriority;
        status: TaskStatus;
        due_date: string;
        estimated_pomodoros: number;
    };
    projects: TaskProject[];
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: typeof props.modelValue): void;
}>();

const form = computed({
    get: () => props.modelValue,
    set: (v) => emit('update:modelValue', v),
});

const inputClass =
    'mt-1 w-full rounded-xl border border-appborder-light bg-surface-light-2 px-3 py-2 text-sm text-gray-900 focus:border-plum-500 focus:outline-none focus:ring-1 focus:ring-plum-500 dark:border-appborder-dark dark:bg-surface-dark-2 dark:text-gray-100';
const labelClass = 'block text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400';
</script>

<template>
    <div class="space-y-4">
        <div>
            <label :class="labelClass">Task Title *</label>
            <input v-model="form.title" type="text" placeholder="e.g. Implement OAuth2 login" required :class="inputClass" />
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label :class="labelClass">Project</label>
                <select v-model="form.project_id" :class="inputClass">
                    <option :value="null">No Project</option>
                    <option v-for="proj in projects" :key="proj.id" :value="proj.id">{{ proj.name }}</option>
                </select>
            </div>
            <div>
                <label :class="labelClass">Priority</label>
                <select v-model="form.priority" :class="inputClass">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label :class="labelClass">Status</label>
                <select v-model="form.status" :class="inputClass">
                    <option value="todo">To Do</option>
                    <option value="in_progress">In Progress</option>
                    <option value="done">Done</option>
                </select>
            </div>
            <div>
                <label :class="labelClass">Due Date</label>
                <input v-model="form.due_date" type="date" :class="inputClass" />
            </div>
        </div>

        <div class="grid grid-cols-3 gap-3">
            <div>
                <label :class="labelClass">Pomodoros</label>
                <input v-model.number="form.estimated_pomodoros" type="number" min="1" max="20" :class="inputClass" />
            </div>
            <div class="col-span-2">
                <label :class="labelClass">Description</label>
                <input v-model="form.description" type="text" placeholder="Notes or context..." :class="inputClass" />
            </div>
        </div>
    </div>
</template>
