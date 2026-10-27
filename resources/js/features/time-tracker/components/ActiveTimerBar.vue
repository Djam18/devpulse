<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { Clock, Play, Square } from 'lucide-vue-next';

import { useTimerStore } from '@/features/time-tracker/store';

const timerStore = useTimerStore();

const descriptionInput = ref('');

async function handleToggleTimer(): Promise<void> {
    if (timerStore.isRunning) {
        await timerStore.stop();
        descriptionInput.value = '';
    } else {
        await timerStore.start({
            description: descriptionInput.value.trim() || null,
        });
    }
}

onMounted(() => {
    timerStore.loadActiveTimer();
});
</script>

<template>
    <div
        class="flex items-center gap-3 rounded-2xl border px-4 py-2 transition-all duration-300 shadow-xs"
        :class="[
            timerStore.isRunning
                ? 'border-peach-300/80 bg-peach-50/70 shadow-peach-500/10 ring-1 ring-peach-500/20 dark:border-peach-400/30 dark:bg-peach-950/30'
                : 'border-appborder-light bg-white/95 backdrop-blur-sm dark:border-appborder-dark dark:bg-surface-dark/95 hover:border-plum-300 dark:hover:border-plum-800/40',
        ]"
    >
        <!-- Description input or Active Task Title -->
        <div class="flex flex-1 items-center gap-2.5 min-w-0">
            <div class="relative flex shrink-0 items-center justify-center">
                <Clock
                    class="h-4 w-4 transition-colors"
                    :class="timerStore.isRunning ? 'text-peach-600 dark:text-peach-400' : 'text-gray-400 dark:text-gray-500'"
                />
                <span
                    v-if="timerStore.isRunning"
                    class="absolute -right-0.5 -top-0.5 h-1.5 w-1.5 rounded-full bg-peach-500 animate-ping"
                ></span>
            </div>

            <!-- Idle text input -->
            <input
                v-if="!timerStore.isRunning"
                v-model="descriptionInput"
                type="text"
                placeholder="What are you working on? (press Enter)"
                class="w-full border-none bg-transparent p-0 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-0 dark:text-gray-100 dark:placeholder-gray-500"
                @keydown.enter="handleToggleTimer"
            />

            <!-- Running active task title with optional project pill -->
            <div v-else class="flex items-center gap-2 min-w-0 truncate">
                <span
                    v-if="timerStore.activeEntry?.project"
                    class="inline-flex items-center gap-1 rounded-md bg-plum-100/80 px-2 py-0.5 text-xs font-semibold text-plum-800 dark:bg-plum-900/60 dark:text-plum-300 shrink-0"
                >
                    <span
                        class="h-1.5 w-1.5 rounded-full"
                        :style="{ backgroundColor: timerStore.activeEntry.project.color }"
                    ></span>
                    <span class="truncate max-w-[100px]">{{ timerStore.activeEntry.project.name }}</span>
                </span>
                <span
                    class="truncate text-sm font-semibold text-gray-900 dark:text-gray-100"
                >
                    {{ timerStore.activeEntry?.description || 'Active focus session...' }}
                </span>
            </div>
        </div>

        <!-- Ticker duration display -->
        <div
            v-if="timerStore.isRunning"
            class="flex items-center gap-1.5 rounded-xl bg-peach-100/80 px-3 py-1 font-mono text-sm font-bold text-peach-800 dark:bg-peach-950/60 dark:text-peach-300 shrink-0 shadow-inner"
        >
            <span class="inline-block h-2 w-2 rounded-full bg-peach-500 animate-pulse"></span>
            <span>{{ timerStore.formattedElapsed }}</span>
        </div>

        <!-- Start / Stop Action Button (Direction D: Start = Plum, Stop = Peach) -->
        <button
            type="button"
            :disabled="timerStore.isLoading"
            class="flex items-center gap-1.5 rounded-xl px-4 py-1.5 text-xs font-bold uppercase tracking-wider shadow-xs transition-all focus:outline-none focus:ring-2 focus:ring-offset-1 disabled:opacity-50 hover:scale-[1.02] active:scale-[0.98]"
            :class="[
                timerStore.isRunning
                    ? 'bg-peach-700 text-white hover:bg-peach-800 focus:ring-peach-600 shadow-peach-700/20 dark:bg-peach-300 dark:text-gray-950 dark:hover:bg-peach-200'
                    : 'bg-plum-700 text-white hover:bg-plum-800 focus:ring-plum-600 shadow-plum-700/20 dark:bg-plum-400 dark:text-gray-950 dark:hover:bg-plum-300',
            ]"
            @click="handleToggleTimer"
        >
            <template v-if="timerStore.isRunning">
                <Square class="h-3.5 w-3.5 fill-current" />
                <span>Stop</span>
            </template>
            <template v-else>
                <Play class="h-3.5 w-3.5 fill-current" />
                <span>Start</span>
            </template>
        </button>
    </div>
</template>
