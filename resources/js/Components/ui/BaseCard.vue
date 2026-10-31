<script setup lang="ts">
withDefaults(
    defineProps<{
        hoverable?: boolean;
        padded?: boolean;
    }>(),
    {
        hoverable: false,
        padded: true,
    },
);
</script>

<template>
    <div
        class="rounded-2xl border border-appborder-light bg-white shadow-xs dark:border-appborder-dark dark:bg-surface-dark transition-all duration-200"
        :class="[
            padded ? 'p-6' : '',
            hoverable ? 'hover:border-plum-300 dark:hover:border-plum-800/60 hover:shadow-md' : '',
        ]"
    >
        <div v-if="$slots.header" class="mb-4">
            <slot name="header" />
        </div>

        <slot />

        <div v-if="$slots.footer" class="mt-4 pt-4 border-t border-appborder-light dark:border-appborder-dark">
            <slot name="footer" />
        </div>
    </div>
</template>
