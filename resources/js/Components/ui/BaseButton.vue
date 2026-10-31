<script setup lang="ts">
import { Loader2 } from 'lucide-vue-next';

withDefaults(
    defineProps<{
        variant?: 'primary' | 'accent' | 'secondary' | 'ghost' | 'danger';
        size?: 'sm' | 'md' | 'lg';
        type?: 'button' | 'submit' | 'reset';
        loading?: boolean;
        disabled?: boolean;
    }>(),
    {
        variant: 'primary',
        size: 'md',
        type: 'button',
        loading: false,
        disabled: false,
    },
);

const variantClasses: Record<string, string> = {
    primary: 'bg-plum-700 text-white hover:bg-plum-800 focus:ring-plum-600 dark:bg-plum-400 dark:text-gray-950 dark:hover:bg-plum-300 shadow-xs',
    accent: 'bg-peach-700 text-white hover:bg-peach-800 focus:ring-peach-600 dark:bg-peach-400 dark:text-gray-950 dark:hover:bg-peach-300 shadow-xs',
    secondary: 'border border-appborder-light bg-white text-gray-700 hover:bg-surface-light-2 focus:ring-plum-500 dark:border-appborder-dark dark:bg-surface-dark dark:text-gray-300 dark:hover:bg-surface-dark-2 shadow-xs',
    ghost: 'text-gray-600 hover:bg-surface-light-2 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-surface-dark-2 dark:hover:text-gray-100',
    danger: 'bg-rose-600 text-white hover:bg-rose-700 focus:ring-rose-500 dark:bg-rose-500 dark:hover:bg-rose-400 shadow-xs',
};

const sizeClasses: Record<string, string> = {
    sm: 'px-2.5 py-1 text-xs font-semibold rounded-lg gap-1.5',
    md: 'px-3.5 py-1.5 text-xs font-bold uppercase tracking-wider rounded-xl gap-2',
    lg: 'px-5 py-2.5 text-sm font-bold rounded-xl gap-2.5',
};
</script>

<template>
    <button
        :type="type"
        :disabled="disabled || loading"
        class="inline-flex items-center justify-center transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-1 disabled:opacity-50 disabled:cursor-not-allowed hover:scale-[1.01] active:scale-[0.98]"
        :class="[
            variantClasses[variant] || variantClasses.primary,
            sizeClasses[size] || sizeClasses.md,
        ]"
    >
        <Loader2 v-if="loading" class="h-3.5 w-3.5 animate-spin" />
        <slot />
    </button>
</template>
