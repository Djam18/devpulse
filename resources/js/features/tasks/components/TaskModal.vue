<script setup lang="ts">
import { ref, watch } from 'vue';
import { X } from 'lucide-vue-next';

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

const form = ref({
    title: '',
    project_id: null as number | null,
    description: '',
    priority: 'medium' as TaskPriority,
    status: 'todo' as TaskStatus,
    due_date: '',
    estimated_pomodoros: 1,
});

const isSubmitting = ref(false);
const errorMessage = ref<string | null>(null);

watch(
    () => props.show,
    (isOpen) => {
        if (!isOpen) return;
        errorMessage.value = null;

        if (props.task) {
            form.value = {
                title: props.task.title,
                project_id: props.task.project_id,
                description: props.task.description || '',
                priority: props.task.priority,
                status: props.task.status,
                due_date: props.task.due_date || '',
                estimated_pomodoros: props.task.estimated_pomodoros || 1,
            };
        } else {
            form.value = {
                title: '',
                project_id: props.projects.length > 0 ? props.projects[0].id : null,
                description: '',
                priority: 'medium',
                status: props.initialStatus || 'todo',
                due_date: '',
                estimated_pomodoros: 1,
            };
        }
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
        let result: Task;
        const payload = {
            title: form.value.title.trim(),
            project_id: form.value.project_id,
            description: form.value.description.trim() || null,
            priority: form.value.priority,
            status: form.value.status,
            due_date: form.value.due_date || null,
            estimated_pomodoros: form.value.estimated_pomodoros,
        };

        if (props.task) {
            result = await taskStore.update(props.task.id, payload);
        } else {
            result = await taskStore.create(payload);
        }

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
    <div
        v-if="show"
        class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-black/50 p-4 backdrop-blur-sm transition-all"
    >
        <div
            class="relative w-full max-w-lg rounded-2xl border border-appborder-light bg-white p-6 shadow-2xl dark:border-appborder-dark dark:bg-surface-dark"
        >
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-appborder-light pb-4 dark:border-appborder-dark">
                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">
                    {{ task ? 'Edit Task' : 'Create New Task' }}
                </h3>
                <button
                    type="button"
                    class="rounded-lg p-1 text-gray-400 hover:bg-surface-light-2 hover:text-gray-600 dark:hover:bg-surface-dark-2 dark:hover:text-gray-200"
                    @click="emit('close')"
                >
                    <X class="h-5 w-5" />
                </button>
            </div>

            <!-- Error banner -->
            <div
                v-if="errorMessage"
                class="mt-4 rounded-lg bg-rose-50 p-3 text-xs text-rose-600 dark:bg-rose-950/40 dark:text-rose-400"
            >
                {{ errorMessage }}
            </div>

            <!-- Form -->
            <form class="mt-4 space-y-4" @submit.prevent="handleSubmit">
                <!-- Title input -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">
                        Task Title *
                    </label>
                    <input
                        v-model="form.title"
                        type="text"
                        placeholder="e.g. Implement OAuth2 multi-factor login"
                        required
                        class="mt-1 w-full rounded-xl border border-appborder-light bg-white px-3.5 py-2 text-sm text-gray-900 shadow-xs focus:border-plum-600 focus:outline-none focus:ring-1 focus:ring-plum-600 dark:border-appborder-dark dark:bg-surface-dark-2 dark:text-gray-100"
                    />
                </div>

                <!-- Project & Priority Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Project -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">
                            Project
                        </label>
                        <select
                            v-model="form.project_id"
                            class="mt-1 w-full rounded-xl border border-appborder-light bg-white px-3.5 py-2 text-sm text-gray-900 shadow-xs focus:border-plum-600 focus:outline-none focus:ring-1 focus:ring-plum-600 dark:border-appborder-dark dark:bg-surface-dark-2 dark:text-gray-100"
                        >
                            <option :value="null">No Project</option>
                            <option
                                v-for="proj in projects"
                                :key="proj.id"
                                :value="proj.id"
                            >
                                {{ proj.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">
                            Priority
                        </label>
                        <select
                            v-model="form.priority"
                            class="mt-1 w-full rounded-xl border border-appborder-light bg-white px-3.5 py-2 text-sm text-gray-900 shadow-xs focus:border-plum-600 focus:outline-none focus:ring-1 focus:ring-plum-600 dark:border-appborder-dark dark:bg-surface-dark-2 dark:text-gray-100"
                        >
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                </div>

                <!-- Status & Due Date Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">
                            Status
                        </label>
                        <select
                            v-model="form.status"
                            class="mt-1 w-full rounded-xl border border-appborder-light bg-white px-3.5 py-2 text-sm text-gray-900 shadow-xs focus:border-plum-600 focus:outline-none focus:ring-1 focus:ring-plum-600 dark:border-appborder-dark dark:bg-surface-dark-2 dark:text-gray-100"
                        >
                            <option value="todo">To Do</option>
                            <option value="in_progress">In Progress</option>
                            <option value="done">Done</option>
                        </select>
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">
                            Due Date
                        </label>
                        <input
                            v-model="form.due_date"
                            type="date"
                            class="mt-1 w-full rounded-xl border border-appborder-light bg-white px-3.5 py-2 text-sm text-gray-900 shadow-xs focus:border-plum-600 focus:outline-none focus:ring-1 focus:ring-plum-600 dark:border-appborder-dark dark:bg-surface-dark-2 dark:text-gray-100"
                        />
                    </div>
                </div>

                <!-- Estimated Pomodoros -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">
                        Estimated Pomodoros (25m sprints)
                    </label>
                    <input
                        v-model.number="form.estimated_pomodoros"
                        type="number"
                        min="1"
                        max="20"
                        class="mt-1 w-full rounded-xl border border-appborder-light bg-white px-3.5 py-2 text-sm text-gray-900 shadow-xs focus:border-plum-600 focus:outline-none focus:ring-1 focus:ring-plum-600 dark:border-appborder-dark dark:bg-surface-dark-2 dark:text-gray-100"
                    />
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">
                        Description
                    </label>
                    <textarea
                        v-model="form.description"
                        rows="3"
                        placeholder="Context, requirements, technical links..."
                        class="mt-1 w-full rounded-xl border border-appborder-light bg-white px-3.5 py-2 text-sm text-gray-900 shadow-xs focus:border-plum-600 focus:outline-none focus:ring-1 focus:ring-plum-600 dark:border-appborder-dark dark:bg-surface-dark-2 dark:text-gray-100"
                    ></textarea>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex items-center justify-end gap-3 border-t border-appborder-light pt-4 dark:border-appborder-dark">
                    <button
                        type="button"
                        class="rounded-xl px-4 py-2 text-sm font-medium text-gray-600 hover:bg-surface-light-2 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-surface-dark-2 dark:hover:text-gray-200"
                        @click="emit('close')"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        :disabled="isSubmitting"
                        class="inline-flex items-center gap-2 rounded-xl bg-plum-700 px-5 py-2 text-sm font-semibold text-white shadow-md shadow-plum-700/20 hover:bg-plum-800 disabled:opacity-50 dark:bg-plum-400 dark:text-gray-950 dark:hover:bg-plum-300"
                    >
                        <span v-if="isSubmitting">Saving...</span>
                        <span v-else>{{ task ? 'Update Task' : 'Create Task' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
