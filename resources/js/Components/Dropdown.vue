<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref } from 'vue';
import { onClickOutside } from '@vueuse/core';

const props = withDefaults(
    defineProps<{
        align?: 'left' | 'right';
        width?: string | number;
        contentClasses?: string;
    }>(),
    {
        align: 'right',
        width: '48',
        contentClasses: 'py-1.5 bg-white border border-appborder-light shadow-xl dark:bg-surface-dark dark:border-appborder-dark rounded-2xl',
    },
);

const open = ref(false);
const dropdownRef = ref<HTMLElement | null>(null);

// Clean cross-browser outside click detection (Firefox, Chrome, Safari)
onClickOutside(dropdownRef, () => {
    if (open.value) {
        open.value = false;
    }
});

const closeOnEscape = (e: KeyboardEvent) => {
    if (open.value && e.key === 'Escape') {
        open.value = false;
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

const widthClass = computed(() => {
    const widthMap: Record<string, string> = {
        '48': 'w-52',
        '56': 'w-56',
        '64': 'w-64',
    };
    return widthMap[props.width.toString()] || 'w-52';
});

const alignmentClasses = computed(() => {
    if (props.align === 'left') {
        return 'origin-top-left left-0';
    }
    return 'origin-top-right right-0';
});

function toggleDropdown(): void {
    open.value = !open.value;
}
</script>

<template>
    <div ref="dropdownRef" class="relative inline-block text-left">
        <!-- Trigger button slot -->
        <div @click="toggleDropdown">
            <slot name="trigger" />
        </div>

        <!-- Dropdown Menu with Vue Transition (no inline display:none) -->
        <Transition
            enter-active-class="transition ease-out duration-150"
            enter-from-class="opacity-0 scale-95 -translate-y-1"
            enter-to-class="opacity-100 scale-100 translate-y-0"
            leave-active-class="transition ease-in duration-100"
            leave-from-class="opacity-100 scale-100 translate-y-0"
            leave-to-class="opacity-0 scale-95 -translate-y-1"
        >
            <div
                v-if="open"
                class="absolute z-50 mt-2 overflow-hidden shadow-xl"
                :class="[widthClass, alignmentClasses, contentClasses]"
                @click="open = false"
            >
                <slot name="content" />
            </div>
        </Transition>
    </div>
</template>
