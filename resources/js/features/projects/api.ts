import axios from 'axios';
import type { Client, ClientPayload, Project, ProjectPayload } from '@/features/projects/types';

export async function fetchClients(): Promise<Client[]> {
    const res = await axios.get<{ data: Client[] }>('/api/v1/clients');
    return res.data.data;
}

export async function createClient(payload: ClientPayload): Promise<Client> {
    const res = await axios.post<{ data: Client }>('/api/v1/clients', payload);
    return res.data.data;
}

export async function updateClient(id: number, payload: ClientPayload): Promise<Client> {
    const res = await axios.put<{ data: Client }>(`/api/v1/clients/${id}`, payload);
    return res.data.data;
}

export async function deleteClient(id: number): Promise<void> {
    await axios.delete(`/api/v1/clients/${id}`);
}

export async function fetchProjects(): Promise<Project[]> {
    const res = await axios.get<{ data: Project[] }>('/api/v1/projects');
    return res.data.data;
}

export async function createProject(payload: ProjectPayload): Promise<Project> {
    const res = await axios.post<{ data: Project }>('/api/v1/projects', payload);
    return res.data.data;
}

export async function updateProject(id: number, payload: ProjectPayload): Promise<Project> {
    const res = await axios.put<{ data: Project }>(`/api/v1/projects/${id}`, payload);
    return res.data.data;
}

export async function deleteProject(id: number): Promise<void> {
    await axios.delete(`/api/v1/projects/${id}`);
}
