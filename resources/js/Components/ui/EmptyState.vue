<script setup lang="ts">
import { Component } from 'vue';
import BaseButton from '@/Components/ui/BaseButton.vue';

withDefaults(
    defineProps<{
        title: string;
        description: string;
        actionLabel?: string;
        actionVariant?: 'primary' | 'accent' | 'secondary';
        compact?: boolean;
    }>(),
    {
        actionVariant: 'primary',
        compact: false,
    },
);

const emit = defineEmits<{
    (e: 'action'): void;
}>();
</script>

<template>
    <div
        class="flex flex-col items-center justify-center text-center rounded-2xl border border-dashed border-appborder-light dark:border-appborder-dark transition-all"
        :class="compact ? 'py-6 px-3' : 'py-12 px-6'"
    >
        <div
            class="flex items-center justify-center rounded-2xl bg-plum-50 text-plum-700 dark:bg-plum-950/40 dark:text-plum-400 mb-3"
            :class="compact ? 'h-10 w-10' : 'h-14 w-14'"
        >
            <slot name="icon" />
        </div>

        <h3
            class="font-bold text-gray-900 dark:text-gray-100"
            :class="compact ? 'text-xs' : 'text-sm'"
        >
            {{ title }}
        </h3>

        <p
            class="mt-1 text-gray-500 dark:text-gray-400 max-w-sm leading-relaxed"
            :class="compact ? 'text-[11px]' : 'text-xs'"
        >
            {{ description }}
        </p>

        <div v-if="actionLabel || $slots.actions" class="mt-4">
            <slot name="actions">
                <BaseButton
                    v-if="actionLabel"
                    :variant="actionVariant"
                    :size="compact ? 'sm' : 'md'"
                    @click="emit('action')"
                >
                    {{ actionLabel }}
                </BaseButton>
            </slot>
        </div>
    </div>
</template>
