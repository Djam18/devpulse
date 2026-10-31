export type TaskStatus = 'todo' | 'in_progress' | 'done';
export type TaskPriority = 'low' | 'medium' | 'high' | 'urgent';

export interface TaskProject {
    id: number;
    name: string;
    color: string;
}

export interface Task {
    id: number;
    title: string;
    description: string | null;
    priority: TaskPriority;
    status: TaskStatus;
    order_column?: number;
    due_date: string | null;
    estimated_pomodoros: number;
    completed_pomodoros: number;
    project_id: number | null;
    project?: TaskProject | null;
    created_at: string;
    updated_at: string;
}

export interface CreateTaskPayload {
    title: string;
    project_id?: number | null;
    description?: string | null;
    priority?: TaskPriority;
    status?: TaskStatus;
    due_date?: string | null;
    estimated_pomodoros?: number;
}

export interface UpdateTaskPayload {
    title: string;
    project_id?: number | null;
    description?: string | null;
    priority: TaskPriority;
    status: TaskStatus;
    due_date?: string | null;
    estimated_pomodoros: number;
}
