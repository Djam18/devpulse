<script setup lang="ts">
withDefaults(
    defineProps<{
        variant?: 'plum' | 'peach' | 'emerald' | 'amber' | 'rose' | 'sky' | 'slate';
        dot?: boolean;
    }>(),
    {
        variant: 'slate',
        dot: false,
    },
);

const variantClasses: Record<string, string> = {
    plum: 'bg-plum-50 text-plum-700 dark:bg-plum-950/50 dark:text-plum-300 border-plum-200/60 dark:border-plum-800/50',
    peach: 'bg-peach-50 text-peach-700 dark:bg-peach-950/50 dark:text-peach-300 border-peach-200/60 dark:border-peach-800/50',
    emerald: 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 border-emerald-200/60 dark:border-emerald-800/50',
    amber: 'bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300 border-amber-200/60 dark:border-amber-800/50',
    rose: 'bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-300 border-rose-200/60 dark:border-rose-800/50',
    sky: 'bg-sky-50 text-sky-700 dark:bg-sky-950/50 dark:text-sky-300 border-sky-200/60 dark:border-sky-800/50',
    slate: 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border-slate-200/60 dark:border-slate-700/50',
};

const dotClasses: Record<string, string> = {
    plum: 'bg-plum-600',
    peach: 'bg-peach-600',
    emerald: 'bg-emerald-600',
    amber: 'bg-amber-600',
    rose: 'bg-rose-600',
    sky: 'bg-sky-600',
    slate: 'bg-slate-500',
};
</script>

<template>
    <span
        class="inline-flex items-center gap-1.5 rounded-lg border px-2 py-0.5 text-xs font-semibold"
        :class="variantClasses[variant] || variantClasses.slate"
    >
        <span
            v-if="dot"
            class="h-1.5 w-1.5 rounded-full shrink-0"
            :class="dotClasses[variant] || dotClasses.slate"
        ></span>
        <slot />
    </span>
</template>
