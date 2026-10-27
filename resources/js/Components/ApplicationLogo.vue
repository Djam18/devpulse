<script setup lang="ts">
withDefaults(
    defineProps<{
        withText?: boolean;
    }>(),
    {
        withText: false,
    },
);
</script>

<template>
    <div class="flex items-center gap-2.5">
        <!-- DevPulse Icon -->
        <div
            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-tr from-plum-700 via-plum-600 to-peach-500 p-2 shadow-md shadow-plum-700/20 ring-1 ring-white/20 transition-all hover:scale-105"
        >
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2.5"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="h-5 w-5 text-white"
            >
                <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
            </svg>
        </div>

        <!-- DevPulse Brand Text -->
        <div v-if="withText" class="flex flex-col">
            <span class="text-base font-extrabold tracking-tight text-gray-900 dark:text-white">
                Dev<span class="bg-gradient-to-r from-plum-700 to-peach-600 bg-clip-text text-transparent dark:from-plum-400 dark:to-peach-300">Pulse</span>
            </span>
            <span class="text-[10px] font-semibold uppercase tracking-wider text-plum-900/50 dark:text-plum-300/60">
                Developer Suite
            </span>
        </div>
    </div>
</template>
