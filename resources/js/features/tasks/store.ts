import { computed, ref } from 'vue';
import { defineStore } from 'pinia';

import * as taskApi from '@/features/tasks/api';
import type { CreateTaskPayload, Task, TaskStatus, UpdateTaskPayload } from '@/features/tasks/types';

export const useTaskStore = defineStore('tasks', () => {
    const tasks = ref<Task[]>([]);
    const isLoading = ref<boolean>(false);
    const selectedProjectId = ref<number | null>(null);
    const searchQuery = ref<string>('');

    const filteredTasks = computed(() => {
        let result = tasks.value;

        if (selectedProjectId.value !== null) {
            result = result.filter((t) => t.project_id === selectedProjectId.value);
        }

        if (searchQuery.value.trim() !== '') {
            const query = searchQuery.value.toLowerCase().trim();
            result = result.filter(
                (t) =>
                    t.title.toLowerCase().includes(query) ||
                    (t.description !== null && t.description.toLowerCase().includes(query)),
            );
        }

        return result;
    });

    const todoTasks = computed(() => filteredTasks.value.filter((t) => t.status === 'todo'));
    const inProgressTasks = computed(() => filteredTasks.value.filter((t) => t.status === 'in_progress'));
    const doneTasks = computed(() => filteredTasks.value.filter((t) => t.status === 'done'));

    async function loadTasks(): Promise<void> {
        isLoading.value = true;
        try {
            tasks.value = await taskApi.fetchTasks();
        } catch (error) {
            console.error('Failed to load tasks:', error);
        } finally {
            isLoading.value = false;
        }
    }

    async function create(payload: CreateTaskPayload): Promise<Task> {
        const newTask = await taskApi.createTask(payload);
        tasks.value.unshift(newTask);
        return newTask;
    }

    async function update(id: number, payload: UpdateTaskPayload): Promise<Task> {
        const updated = await taskApi.updateTask(id, payload);
        const index = tasks.value.findIndex((t) => t.id === id);
        if (index !== -1) {
            tasks.value[index] = updated;
        }
        return updated;
    }

    async function changeStatus(id: number, newStatus: TaskStatus): Promise<void> {
        const task = tasks.value.find((t) => t.id === id);
        const previousStatus = task ? task.status : null;

        if (task) {
            task.status = newStatus;
        }

        try {
            const updated = await taskApi.updateTaskStatus(id, newStatus);
            const index = tasks.value.findIndex((t) => t.id === id);
            if (index !== -1) {
                tasks.value[index] = updated;
            }
        } catch (error) {
            if (task && previousStatus !== null) {
                task.status = previousStatus;
            }
            throw error;
        }
    }

    async function remove(id: number): Promise<void> {
        await taskApi.deleteTask(id);
        tasks.value = tasks.value.filter((t) => t.id !== id);
    }

    async function reorder(taskIds: number[], status?: TaskStatus): Promise<void> {
        // Optimistically update status and order_column locally
        taskIds.forEach((id, index) => {
            const task = tasks.value.find((t) => t.id === id);
            if (task) {
                task.order_column = index;
                if (status) {
                    task.status = status;
                }
            }
        });

        await taskApi.reorderTasks(taskIds, status);
    }

    return {
        tasks,
        isLoading,
        selectedProjectId,
        searchQuery,
        filteredTasks,
        todoTasks,
        inProgressTasks,
        doneTasks,
        loadTasks,
        create,
        update,
        changeStatus,
        reorder,
        remove,
    };
});
