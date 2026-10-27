import { computed, ref } from 'vue';
import { defineStore } from 'pinia';

import * as timerApi from '@/features/time-tracker/api';
import type { StartTimerPayload, TimeEntry } from '@/features/time-tracker/types';

export const useTimerStore = defineStore('timer', () => {
    const activeEntry = ref<TimeEntry | null>(null);
    const elapsedSeconds = ref<number>(0);
    const isLoading = ref<boolean>(false);
    let intervalId: number | null = null;

    const isRunning = computed(() => activeEntry.value !== null);

    const formattedElapsed = computed(() => {
        const total = elapsedSeconds.value;
        const hours = Math.floor(total / 3600);
        const minutes = Math.floor((total % 3600) / 60);
        const seconds = total % 60;

        const pad = (n: number) => n.toString().padStart(2, '0');

        if (hours > 0) {
            return `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
        }
        return `${pad(minutes)}:${pad(seconds)}`;
    });

    function recalculateElapsed() {
        if (!activeEntry.value) {
            elapsedSeconds.value = 0;
            return;
        }

        const startedMs = Date.parse(activeEntry.value.started_at);
        const nowMs = Date.now();
        elapsedSeconds.value = Math.max(0, Math.floor((nowMs - startedMs) / 1000));
    }

    function startTicker() {
        stopTicker();
        recalculateElapsed();
        intervalId = window.setInterval(() => {
            elapsedSeconds.value++;
        }, 1000);
    }

    function stopTicker() {
        if (intervalId !== null) {
            clearInterval(intervalId);
            intervalId = null;
        }
        elapsedSeconds.value = 0;
    }

    async function loadActiveTimer(): Promise<void> {
        try {
            isLoading.value = true;
            const active = await timerApi.fetchActiveTimer();
            activeEntry.value = active;
            if (active) {
                startTicker();
            } else {
                stopTicker();
            }
        } catch (error) {
            console.error('Failed to load active timer:', error);
        } finally {
            isLoading.value = false;
        }
    }

    async function start(payload: StartTimerPayload = {}): Promise<TimeEntry | null> {
        try {
            isLoading.value = true;
            const entry = await timerApi.startTimer(payload);
            activeEntry.value = entry;
            startTicker();
            return entry;
        } catch (error) {
            console.error('Failed to start timer:', error);
            throw error;
        } finally {
            isLoading.value = false;
        }
    }

    async function stop(): Promise<TimeEntry | null> {
        if (!activeEntry.value) return null;

        try {
            isLoading.value = true;
            const stopped = await timerApi.stopTimer(activeEntry.value.id);
            activeEntry.value = null;
            stopTicker();
            return stopped;
        } catch (error) {
            console.error('Failed to stop timer:', error);
            throw error;
        } finally {
            isLoading.value = false;
        }
    }

    return {
        activeEntry,
        elapsedSeconds,
        isLoading,
        isRunning,
        formattedElapsed,
        loadActiveTimer,
        start,
        stop,
    };
});
