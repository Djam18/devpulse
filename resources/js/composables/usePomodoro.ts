import { ref, computed } from 'vue';
import axios from 'axios';
import { useTaskStore } from '@/features/tasks/store';

export type PomodoroMode = 'work' | 'break';

export function usePomodoro() {
    const taskStore = useTaskStore();

    const mode = ref<PomodoroMode>('work');
    const isRunning = ref(false);
    const activeTaskId = ref<number | null>(null);
    const timeLeft = ref<number>(25 * 60); // 25 minutes par défaut
    let timerInterval: ReturnType<typeof setInterval> | null = null;

    const formattedTime = computed(() => {
        const mins = Math.floor(timeLeft.value / 60);
        const secs = timeLeft.value % 60;
        return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
    });

    // Sonnerie Web Audio API (carillon cristallin deux tons)
    function playChime(): void {
        try {
            const AudioCtx = window.AudioContext || (window as any).webkitAudioContext;
            if (!AudioCtx) return;
            const ctx = new AudioCtx();

            const playTone = (freq: number, start: number, dur: number) => {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, ctx.currentTime + start);
                gain.gain.setValueAtTime(0.15, ctx.currentTime + start);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + start + dur);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(ctx.currentTime + start);
                osc.stop(ctx.currentTime + start + dur);
            };

            playTone(880, 0, 0.4);      // A5
            playTone(1318.51, 0.25, 0.6); // E6
        } catch {
            // Ignorer si audio bloqué par politique autoplay du navigateur
        }
    }

    function start(taskId?: number): void {
        if (taskId) activeTaskId.value = taskId;
        isRunning.value = true;

        if (timerInterval) clearInterval(timerInterval);

        timerInterval = setInterval(() => {
            if (timeLeft.value > 0) {
                timeLeft.value--;
            } else {
                handleComplete();
            }
        }, 1000);
    }

    function pause(): void {
        isRunning.value = false;
        if (timerInterval) {
            clearInterval(timerInterval);
            timerInterval = null;
        }
    }

    function reset(targetMode?: PomodoroMode): void {
        pause();
        if (targetMode) mode.value = targetMode;
        timeLeft.value = mode.value === 'work' ? 25 * 60 : 5 * 60;
    }

    async function handleComplete(): Promise<void> {
        pause();
        playChime();

        if (mode.value === 'work') {
            // Incrémenter en base pour la tâche si sélectionnée
            if (activeTaskId.value) {
                try {
                    await axios.post(route('api.v1.tasks.pomodoro', activeTaskId.value));
                    await taskStore.loadTasks();
                } catch (e) {
                    console.error('Erreur sauvegarde pomodoro', e);
                }
            }
            // Basculer vers 5 minutes de pause
            mode.value = 'break';
            timeLeft.value = 5 * 60;
        } else {
            // Fin de la pause, prêt pour le travail
            mode.value = 'work';
            timeLeft.value = 25 * 60;
        }
    }

    return {
        mode,
        isRunning,
        activeTaskId,
        timeLeft,
        formattedTime,
        start,
        pause,
        reset,
    };
}
