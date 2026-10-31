export interface Client {
    id: number;
    name: string;
    color: string;
    hourly_rate: number | null;
    projects_count?: number;
    created_at?: string;
    updated_at?: string;
}

export type ProjectStatus = 'active' | 'archived' | 'completed';

export interface Project {
    id: number;
    client_id: number | null;
    name: string;
    color: string;
    estimated_hours: number;
    logged_hours?: number;
    status: ProjectStatus;
    client?: Client | null;
    tasks_count?: number;
    created_at?: string;
    updated_at?: string;
}

export interface ClientPayload {
    name: string;
    color?: string;
    hourly_rate?: number | null;
}

export interface ProjectPayload {
    name: string;
    color?: string;
    client_id?: number | null;
    estimated_hours?: number;
    status?: ProjectStatus;
}
