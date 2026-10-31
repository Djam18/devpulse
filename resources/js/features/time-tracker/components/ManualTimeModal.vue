<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { Clock } from 'lucide-vue-next';

import { BaseButton, BaseModal } from '@/Components/ui';
import { useTimerStore } from '@/features/time-tracker/store';
import { formatSecondsToHuman } from '@/lib/formatters';

const props = defineProps<{ show: boolean }>();
const emit = defineEmits<{ (e: 'close'): void; (e: 'saved'): void }>();
const timerStore = useTimerStore();

const toLocalIso = (d: Date) => {
    const pad = (n: number) => n.toString().padStart(2, '0');
    return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
};

const form = ref({
    description: '',
    started_at: toLocalIso(new Date(Date.now() - 3600000)),
    ended_at: toLocalIso(new Date()),
});
const isSubmitting = ref(false);
const error = ref<string | null>(null);

watch(
    () => props.show,
    (open) => {
        if (!open) return;
        error.value = null;
        form.value = {
            description: '',
            started_at: toLocalIso(new Date(Date.now() - 3600000)),
            ended_at: toLocalIso(new Date()),
        };
    },
);

const durationSec = computed(() => {
    const s = Date.parse(form.value.started_at);
    const e = Date.parse(form.value.ended_at);
    return isNaN(s) || isNaN(e) ? 0 : Math.max(0, Math.floor((e - s) / 1000));
});

async function submit(): Promise<void> {
    error.value = null;
    const s = Date.parse(form.value.started_at);
    const e = Date.parse(form.value.ended_at);

    if (e <= s) {
        error.value = 'End time must be after start time.';
        return;
    }
    if (s > Date.now()) {
        error.value = 'Start time cannot be in the future.';
        return;
    }

    try {
        isSubmitting.value = true;
        await timerStore.logManual({
            description: form.value.description.trim() || null,
            started_at: new Date(s).toISOString(),
            ended_at: new Date(e).toISOString(),
        });
        emit('saved');
        emit('close');
    } catch (err: any) {
        error.value = err.response?.data?.message || 'Failed to log past time.';
    } finally {
        isSubmitting.value = false;
    }
}
</script>

<template>
    <BaseModal :show="show" title="Log Past Time" max-width="md" @close="emit('close')">
        <form class="space-y-4" @submit.prevent="submit">
            <div v-if="error" class="rounded-lg bg-rose-50 p-2.5 text-xs font-semibold text-rose-600 dark:bg-rose-950/40 dark:text-rose-400">
                {{ error }}
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Description</label>
                <input
                    v-model="form.description"
                    type="text"
                    placeholder="What did you work on?"
                    class="w-full rounded-xl border border-appborder-light bg-surface-light-2 px-3 py-2 text-sm text-gray-900 focus:border-plum-500 focus:ring-1 focus:ring-plum-500 dark:border-appborder-dark dark:bg-surface-dark-2 dark:text-gray-100"
                />
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Start Time</label>
                    <input
                        v-model="form.started_at"
                        type="datetime-local"
                        required
                        class="w-full rounded-xl border border-appborder-light bg-surface-light-2 px-2.5 py-1.5 text-xs text-gray-900 focus:border-plum-500 focus:ring-1 focus:ring-plum-500 dark:border-appborder-dark dark:bg-surface-dark-2 dark:text-gray-100"
                    />
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">End Time</label>
                    <input
                        v-model="form.ended_at"
                        type="datetime-local"
                        required
                        class="w-full rounded-xl border border-appborder-light bg-surface-light-2 px-2.5 py-1.5 text-xs text-gray-900 focus:border-plum-500 focus:ring-1 focus:ring-plum-500 dark:border-appborder-dark dark:bg-surface-dark-2 dark:text-gray-100"
                    />
                </div>
            </div>

            <div class="flex items-center justify-between rounded-xl bg-surface-light-2 px-3 py-2 text-xs font-medium text-gray-600 dark:bg-surface-dark-2 dark:text-gray-300">
                <span class="flex items-center gap-1.5"><Clock class="h-3.5 w-3.5 text-plum-600" /> Duration:</span>
                <span class="font-mono font-bold">{{ formatSecondsToHuman(durationSec) }}</span>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-appborder-light dark:border-appborder-dark">
                <BaseButton variant="ghost" size="sm" @click="emit('close')">Cancel</BaseButton>
                <BaseButton type="submit" variant="primary" size="sm" :loading="isSubmitting" :disabled="durationSec <= 0">
                    Log Time
                </BaseButton>
            </div>
        </form>
    </BaseModal>
</template>
