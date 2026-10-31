<script setup lang="ts">
import { X } from 'lucide-vue-next';

withDefaults(
    defineProps<{
        show: boolean;
        title?: string;
        maxWidth?: 'sm' | 'md' | 'lg' | 'xl';
    }>(),
    {
        title: undefined,
        maxWidth: 'md',
    },
);

const emit = defineEmits<{
    (e: 'close'): void;
}>();

const maxWidthClasses = {
    sm: 'max-w-sm',
    md: 'max-w-md',
    lg: 'max-w-lg',
    xl: 'max-w-xl',
};
</script>

<template>
    <div
        v-if="show"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs transition-opacity"
        @click.self="emit('close')"
    >
        <div
            class="w-full rounded-2xl border border-appborder-light bg-white p-6 shadow-2xl dark:border-appborder-dark dark:bg-surface-dark space-y-4 transition-all"
            :class="maxWidthClasses[maxWidth] || maxWidthClasses.md"
        >
            <div
                v-if="title || $slots.header"
                class="flex items-center justify-between pb-3 border-b border-appborder-light dark:border-appborder-dark"
            >
                <slot name="header">
                    <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">
                        {{ title }}
                    </h3>
                </slot>
                <button
                    type="button"
                    class="rounded-lg p-1 text-gray-400 hover:bg-surface-light-2 hover:text-gray-600 dark:hover:bg-surface-dark-2 dark:hover:text-gray-200 transition-colors"
                    @click="emit('close')"
                >
                    <X class="h-4 w-4" />
                </button>
            </div>

            <div>
                <slot />
            </div>

            <div
                v-if="$slots.footer"
                class="flex items-center justify-end gap-2 pt-3 border-t border-appborder-light dark:border-appborder-dark"
            >
                <slot name="footer" />
            </div>
        </div>
    </div>
</template>
