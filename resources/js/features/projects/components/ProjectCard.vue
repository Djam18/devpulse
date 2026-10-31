<script setup lang="ts">
import { computed } from 'vue';
import { Building2, Clock, Edit2, ListTodo, Trash2 } from 'lucide-vue-next';
import type { Project } from '@/features/projects/types';

const props = defineProps<{ project: Project }>();
const emit = defineEmits<{ (e: 'edit', project: Project): void; (e: 'delete', id: number): void }>();

const logged = computed(() => props.project.logged_hours ?? 0);
const estimated = computed(() => props.project.estimated_hours ?? 0);

const burnRate = computed(() => {
    if (estimated.value <= 0) return 0;
    return Math.round((logged.value / estimated.value) * 100);
});

const progressColor = computed(() => {
    if (burnRate.value > 100) return 'bg-rose-500 text-rose-700 dark:text-rose-400';
    if (burnRate.value >= 80) return 'bg-amber-500 text-amber-700 dark:text-amber-400';
    return 'bg-emerald-500 text-emerald-700 dark:text-emerald-400';
});
</script>

<template>
    <div class="rounded-2xl border border-appborder-light bg-white p-5 shadow-xs transition-all hover:border-plum-300 dark:border-appborder-dark dark:bg-surface-dark dark:hover:border-plum-800 flex flex-col justify-between">
        <div>
            <!-- Header: Color + Title + Status -->
            <div class="flex items-start justify-between gap-2">
                <div class="flex items-center gap-2">
                    <span class="h-3 w-3 rounded-full shrink-0" :style="{ backgroundColor: project.color }"></span>
                    <h3 class="font-bold text-sm text-gray-900 dark:text-gray-100 truncate" :title="project.name">{{ project.name }}</h3>
                </div>
                <span
                    :class="[
                        'rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider',
                        project.status === 'active'
                            ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300'
                            : 'bg-gray-100 text-gray-600 dark:bg-surface-dark-2 dark:text-gray-400',
                    ]"
                >
                    {{ project.status }}
                </span>
            </div>

            <!-- Client Info -->
            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                <Building2 class="h-3.5 w-3.5 shrink-0" />
                <span v-if="project.client" class="truncate">
                    {{ project.client.name }}
                    <strong v-if="project.client.hourly_rate" class="ml-1 text-gray-700 dark:text-gray-300">({{ project.client.hourly_rate }} €/h)</strong>
                </span>
                <span v-else class="italic">Projet interne (non facturé)</span>
            </p>

            <!-- Budget Progress Gauge (Idée 4) -->
            <div v-if="estimated > 0" class="mt-4 rounded-xl border border-appborder-light bg-surface-light-2 p-3 dark:border-appborder-dark dark:bg-surface-dark-2/60">
                <div class="flex items-center justify-between text-xs mb-1.5">
                    <span class="text-gray-600 dark:text-gray-400 flex items-center gap-1 font-medium">
                        <Clock class="h-3 w-3" /> {{ logged }}h / {{ estimated }}h
                    </span>
                    <span class="font-mono text-xs font-bold" :class="progressColor.split(' ')[1]">
                        {{ burnRate }}%
                    </span>
                </div>
                <!-- Mini Progress Track -->
                <div class="h-2 w-full rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                    <div
                        class="h-full transition-all duration-300"
                        :class="progressColor.split(' ')[0]"
                        :style="{ width: `${Math.min(100, burnRate)}%` }"
                    ></div>
                </div>
            </div>
            <div v-else class="mt-4 text-xs text-gray-400 dark:text-gray-500 italic">
                Aucun budget horaire défini
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="mt-4 pt-3 border-t border-appborder-light dark:border-appborder-dark flex items-center justify-between text-xs">
            <span class="text-gray-500 flex items-center gap-1">
                <ListTodo class="h-3.5 w-3.5" /> {{ project.tasks_count ?? 0 }} tâche(s)
            </span>
            <div class="flex items-center gap-1">
                <button type="button" title="Modifier le projet" class="p-1 text-gray-400 hover:text-plum-700 dark:hover:text-plum-300" @click="emit('edit', project)">
                    <Edit2 class="h-3.5 w-3.5" />
                </button>
                <button type="button" title="Supprimer le projet" class="p-1 text-gray-400 hover:text-rose-600 dark:hover:text-rose-400" @click="emit('delete', project.id)">
                    <Trash2 class="h-3.5 w-3.5" />
                </button>
            </div>
        </div>
    </div>
</template>
