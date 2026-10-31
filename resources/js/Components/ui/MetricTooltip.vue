<script setup lang="ts">
import { ref } from 'vue';
import { HelpCircle } from 'lucide-vue-next';

withDefaults(
    defineProps<{
        text: string;
        position?: 'top' | 'bottom';
        size?: 'sm' | 'md';
    }>(),
    {
        position: 'top',
        size: 'sm',
    },
);

const isVisible = ref(false);
</script>

<template>
    <div
        class="relative inline-flex items-center cursor-help select-none"
        @mouseenter="isVisible = true"
        @mouseleave="isVisible = false"
        @focusin="isVisible = true"
        @focusout="isVisible = false"
    >
        <button
            type="button"
            class="text-gray-400 hover:text-plum-600 focus:outline-none focus:text-plum-600 dark:text-gray-500 dark:hover:text-plum-400 transition-colors"
            tabindex="0"
            :aria-label="text"
        >
            <slot name="trigger">
                <HelpCircle :class="size === 'sm' ? 'h-3.5 w-3.5' : 'h-4 w-4'" />
            </slot>
        </button>

        <!-- Floating Popover Tooltip -->
        <Transition
            enter-active-class="transition duration-150 ease-out"
            enter-from-class="opacity-0 translate-y-1 scale-95"
            enter-to-class="opacity-100 translate-y-0 scale-100"
            leave-active-class="transition duration-100 ease-in"
            leave-from-class="opacity-100 translate-y-0 scale-100"
            leave-to-class="opacity-0 translate-y-1 scale-95"
        >
            <div
                v-if="isVisible"
                role="tooltip"
                class="absolute z-50 w-64 rounded-xl border border-appborder-light bg-gray-900/95 px-3 py-2 text-left text-xs font-medium text-white shadow-xl backdrop-blur-sm dark:border-appborder-dark dark:bg-surface-dark-2 dark:text-gray-200 pointer-events-none"
                :class="[
                    position === 'top'
                        ? 'bottom-full left-1/2 -translate-x-1/2 mb-2'
                        : 'top-full left-1/2 -translate-x-1/2 mt-2',
                ]"
            >
                <p class="leading-relaxed">{{ text }}</p>
            </div>
        </Transition>
    </div>
</template>
