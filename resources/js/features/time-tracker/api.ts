import axios from 'axios';

import type { LogManualTimePayload, StartTimerPayload, TimeEntry } from '@/features/time-tracker/types';

export async function fetchActiveTimer(): Promise<TimeEntry | null> {
    const response = await axios.get<{ data: TimeEntry | null }>('/api/v1/time-entries/active');
    return response.data.data;
}

export async function startTimer(payload: StartTimerPayload): Promise<TimeEntry> {
    const response = await axios.post<{ data: TimeEntry }>('/api/v1/time-entries/start', payload);
    return response.data.data;
}

export async function stopTimer(id: number): Promise<TimeEntry> {
    const response = await axios.patch<{ data: TimeEntry }>(`/api/v1/time-entries/${id}/stop`);
    return response.data.data;
}

export async function fetchRecentTimeEntries(): Promise<TimeEntry[]> {
    const response = await axios.get<{ data: TimeEntry[] }>('/api/v1/time-entries');
    return response.data.data;
}

export async function logManualTime(payload: LogManualTimePayload): Promise<TimeEntry> {
    const response = await axios.post<{ data: TimeEntry }>('/api/v1/time-entries', payload);
    return response.data.data;
}

export async function deleteTimeEntry(id: number): Promise<void> {
    await axios.delete(`/api/v1/time-entries/${id}`);
}
