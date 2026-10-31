<script setup lang="ts">
withDefaults(
    defineProps<{
        modelValue?: string | number | null;
        label?: string;
        type?: string;
        placeholder?: string;
        error?: string | null;
        required?: boolean;
        disabled?: boolean;
        id?: string;
    }>(),
    {
        modelValue: '',
        label: undefined,
        type: 'text',
        placeholder: '',
        error: null,
        required: false,
        disabled: false,
        id: undefined,
    },
);

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();
</script>

<template>
    <div class="space-y-1">
        <label
            v-if="label"
            :for="id"
            class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300"
        >
            {{ label }}
            <span v-if="required" class="text-rose-500">*</span>
        </label>
        <input
            :id="id"
            :type="type"
            :value="modelValue"
            :placeholder="placeholder"
            :required="required"
            :disabled="disabled"
            class="w-full rounded-xl border border-appborder-light bg-surface-light-2 px-3 py-2 text-sm text-gray-900 placeholder-gray-400 transition-all focus:border-plum-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-plum-500 disabled:opacity-50 dark:border-appborder-dark dark:bg-surface-dark-2 dark:text-gray-100 dark:focus:bg-surface-dark-2"
            :class="{ 'border-rose-500 focus:border-rose-500 focus:ring-rose-500': error }"
            @input="emit('update:modelValue', ($event.target as HTMLInputElement).value)"
        />
        <p v-if="error" class="text-xs font-medium text-rose-600 dark:text-rose-400">
            {{ error }}
        </p>
    </div>
</template>
