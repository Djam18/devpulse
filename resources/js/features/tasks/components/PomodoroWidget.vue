<script setup lang="ts">
import { Play, Pause, RotateCcw, Coffee, Sparkles } from 'lucide-vue-next';
import { usePomodoro } from '@/composables/usePomodoro';
import BaseButton from '@/Components/ui/BaseButton.vue';

const { mode, isRunning, formattedTime, start, pause, reset } = usePomodoro();
</script>

<template>
    <div class="rounded-2xl border border-appborder-light bg-white p-5 shadow-xs dark:border-appborder-dark dark:bg-surface-dark space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-xl">🍅</span>
                <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">
                    Pomodoro Deep Work
                </h3>
            </div>

            <div class="inline-flex rounded-xl border border-appborder-light p-1 bg-surface-light-2 dark:border-appborder-dark dark:bg-surface-dark-2">
                <button
                    type="button"
                    class="rounded-lg px-2.5 py-1 text-xs font-semibold transition-all"
                    :class="mode === 'work' ? 'bg-white text-plum-700 shadow-xs dark:bg-surface-dark dark:text-plum-300' : 'text-gray-500'"
                    @click="reset('work')"
                >
                    Focus (25m)
                </button>
                <button
                    type="button"
                    class="rounded-lg px-2.5 py-1 text-xs font-semibold transition-all"
                    :class="mode === 'break' ? 'bg-white text-peach-700 shadow-xs dark:bg-surface-dark dark:text-peach-400' : 'text-gray-500'"
                    @click="reset('break')"
                >
                    Pause (5m)
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between rounded-xl p-4" :class="mode === 'work' ? 'bg-plum-50/60 dark:bg-plum-950/20' : 'bg-peach-50/60 dark:bg-peach-950/20'">
            <div>
                <p class="font-mono text-3xl sm:text-4xl font-black" :class="mode === 'work' ? 'text-plum-700 dark:text-plum-400' : 'text-peach-700 dark:text-peach-400'">
                    {{ formattedTime }}
                </p>
                <p class="mt-1 text-xs font-medium text-gray-500 flex items-center gap-1.5">
                    <Coffee v-if="mode === 'break'" class="h-3.5 w-3.5 text-peach-600" />
                    <Sparkles v-else class="h-3.5 w-3.5 text-plum-600" />
                    <span>{{ mode === 'work' ? 'Session de travail intense' : 'Pause récupération' }}</span>
                </p>
            </div>

            <div class="flex items-center gap-2">
                <BaseButton
                    v-if="!isRunning"
                    variant="primary"
                    size="sm"
                    @click="start()"
                >
                    <Play class="h-4 w-4 fill-current" />
                    <span>Démarrer</span>
                </BaseButton>

                <BaseButton
                    v-else
                    variant="secondary"
                    size="sm"
                    @click="pause()"
                >
                    <Pause class="h-4 w-4" />
                    <span>Pause</span>
                </BaseButton>

                <button
                    type="button"
                    title="Réinitialiser"
                    class="rounded-xl border border-appborder-light p-2 text-gray-400 hover:text-gray-700 dark:border-appborder-dark dark:hover:text-gray-200"
                    @click="reset()"
                >
                    <RotateCcw class="h-4 w-4" />
                </button>
            </div>
        </div>
    </div>
</template>
