<script setup lang="ts">
import { reactive, watch } from 'vue';
import { X } from 'lucide-vue-next';
import type { Client, Project, ProjectPayload, ProjectStatus } from '@/features/projects/types';

const props = defineProps<{
    show: boolean;
    project: Project | null;
    clients: Client[];
}>();

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'save', payload: ProjectPayload): void;
}>();

const form = reactive<ProjectPayload>({
    name: '',
    color: '#7D2D68',
    client_id: null,
    estimated_hours: 0,
    status: 'active',
});

watch(
    () => props.project,
    (p) => {
        if (p) {
            form.name = p.name;
            form.color = p.color;
            form.client_id = p.client_id;
            form.estimated_hours = p.estimated_hours;
            form.status = p.status;
        } else {
            form.name = '';
            form.color = '#7D2D68';
            form.client_id = null;
            form.estimated_hours = 0;
            form.status = 'active';
        }
    },
    { immediate: true },
);

function submit(): void {
    if (!form.name.trim()) return;
    emit('save', {
        name: form.name.trim(),
        color: form.color,
        client_id: form.client_id ? Number(form.client_id) : null,
        estimated_hours: Number(form.estimated_hours) || 0,
        status: form.status,
    });
}
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-xs">
        <div class="w-full max-w-lg rounded-2xl border border-appborder-light bg-white p-6 shadow-xl dark:border-appborder-dark dark:bg-surface-dark">
            <div class="flex items-center justify-between pb-4 border-b border-appborder-light dark:border-appborder-dark">
                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">
                    {{ project ? 'Modifier le projet' : 'Nouveau projet' }}
                </h3>
                <button type="button" class="rounded-lg p-1 text-gray-400 hover:bg-surface-light-2 dark:hover:bg-surface-dark-2" @click="emit('close')">
                    <X class="h-4 w-4" />
                </button>
            </div>

            <form class="mt-4 space-y-4" @submit.prevent="submit">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Nom du projet</label>
                    <input
                        v-model="form.name"
                        type="text"
                        required
                        placeholder="ex: Refonte API Mobile"
                        class="mt-1 block w-full rounded-xl border border-appborder-light bg-surface-light-2 px-3 py-2 text-sm text-gray-900 focus:border-plum-500 focus:ring-1 focus:ring-plum-500 dark:border-appborder-dark dark:bg-surface-dark-2 dark:text-gray-100"
                    />
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Client rattaché</label>
                        <select
                            v-model="form.client_id"
                            class="mt-1 block w-full rounded-xl border border-appborder-light bg-surface-light-2 px-3 py-2 text-sm text-gray-900 focus:border-plum-500 focus:ring-1 focus:ring-plum-500 dark:border-appborder-dark dark:bg-surface-dark-2 dark:text-gray-100"
                        >
                            <option :value="null">-- Aucun client (Projet interne) --</option>
                            <option v-for="c in clients" :key="c.id" :value="c.id">{{ c.name }} ({{ c.hourly_rate ? c.hourly_rate + ' €/h' : 'Sans taux' }})</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Statut</label>
                        <select
                            v-model="form.status"
                            class="mt-1 block w-full rounded-xl border border-appborder-light bg-surface-light-2 px-3 py-2 text-sm text-gray-900 focus:border-plum-500 focus:ring-1 focus:ring-plum-500 dark:border-appborder-dark dark:bg-surface-dark-2 dark:text-gray-100"
                        >
                            <option value="active">Actif</option>
                            <option value="completed">Terminé</option>
                            <option value="archived">Archivé</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Couleur d'accent</label>
                        <div class="mt-1 flex items-center gap-2">
                            <input v-model="form.color" type="color" class="h-9 w-12 cursor-pointer rounded-lg border border-appborder-light bg-transparent p-0.5 dark:border-appborder-dark" />
                            <span class="font-mono text-xs text-gray-500">{{ form.color }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Budget estimé (heures)</label>
                        <input
                            v-model.number="form.estimated_hours"
                            type="number"
                            min="0"
                            step="0.5"
                            class="mt-1 block w-full rounded-xl border border-appborder-light bg-surface-light-2 px-3 py-2 text-sm text-gray-900 focus:border-plum-500 focus:ring-1 focus:ring-plum-500 dark:border-appborder-dark dark:bg-surface-dark-2 dark:text-gray-100"
                        />
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-appborder-light dark:border-appborder-dark">
                    <button type="button" class="rounded-xl border border-appborder-light px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-surface-light-2 dark:border-appborder-dark dark:text-gray-300 dark:hover:bg-surface-dark-2" @click="emit('close')">
                        Annuler
                    </button>
                    <button type="submit" class="rounded-xl bg-plum-700 px-4 py-2 text-xs font-semibold text-white shadow-xs hover:bg-plum-800 dark:bg-plum-600 dark:hover:bg-plum-500">
                        {{ project ? 'Enregistrer' : 'Créer le projet' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
