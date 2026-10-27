export interface ProjectSummary {
    id: number;
    name: string;
    color: string;
}

export interface TaskSummary {
    id: number;
    title: string;
    status: string;
}

export interface TimeEntry {
    id: number;
    description: string | null;
    started_at: string;
    ended_at: string | null;
    duration_seconds: number;
    is_running: boolean;
    is_pomodoro: boolean;
    project?: ProjectSummary | null;
    task?: TaskSummary | null;
    created_at?: string;
}

export interface StartTimerPayload {
    description?: string | null;
    project_id?: number | null;
    task_id?: number | null;
    is_pomodoro?: boolean;
}

export interface LogManualTimePayload {
    started_at: string;
    ended_at: string;
    description?: string | null;
    project_id?: number | null;
    task_id?: number | null;
    is_pomodoro?: boolean;
}
