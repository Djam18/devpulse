import axios from 'axios';

import type { CreateTaskPayload, Task, TaskStatus, UpdateTaskPayload } from '@/features/tasks/types';

export async function fetchTasks(params?: { project_id?: number; status?: TaskStatus }): Promise<Task[]> {
    const response = await axios.get<{ data: Task[] }>('/api/v1/tasks', { params });
    return response.data.data;
}

export async function createTask(payload: CreateTaskPayload): Promise<Task> {
    const response = await axios.post<{ data: Task }>('/api/v1/tasks', payload);
    return response.data.data;
}

export async function updateTask(id: number, payload: UpdateTaskPayload): Promise<Task> {
    const response = await axios.put<{ data: Task }>(`/api/v1/tasks/${id}`, payload);
    return response.data.data;
}

export async function updateTaskStatus(id: number, status: TaskStatus): Promise<Task> {
    const response = await axios.patch<{ data: Task }>(`/api/v1/tasks/${id}/status`, { status });
    return response.data.data;
}

export async function deleteTask(id: number): Promise<void> {
    await axios.delete(`/api/v1/tasks/${id}`);
}
