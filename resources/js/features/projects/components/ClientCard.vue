<script setup lang="ts">
import { Briefcase, Edit2, Trash2 } from 'lucide-vue-next';
import type { Client } from '@/features/projects/types';

defineProps<{ client: Client }>();
const emit = defineEmits<{ (e: 'edit', client: Client): void; (e: 'delete', id: number): void }>();
</script>

<template>
    <div class="rounded-2xl border border-appborder-light bg-white p-5 shadow-xs transition-all hover:border-plum-300 dark:border-appborder-dark dark:bg-surface-dark dark:hover:border-plum-800 flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <span class="h-3.5 w-3.5 rounded-full shrink-0" :style="{ backgroundColor: client.color }"></span>
                <h3 class="font-bold text-sm text-gray-900 dark:text-gray-100 truncate" :title="client.name">{{ client.name }}</h3>
            </div>
            <div class="flex items-center gap-1">
                <button type="button" title="Modifier le client" class="p-1 text-gray-400 hover:text-plum-700 dark:hover:text-plum-300" @click="emit('edit', client)">
                    <Edit2 class="h-3.5 w-3.5" />
                </button>
                <button type="button" title="Supprimer le client" class="p-1 text-gray-400 hover:text-rose-600 dark:hover:text-rose-400" @click="emit('delete', client.id)">
                    <Trash2 class="h-3.5 w-3.5" />
                </button>
            </div>
        </div>

        <div class="mt-4 pt-3 border-t border-appborder-light dark:border-appborder-dark flex items-center justify-between text-xs text-gray-500">
            <span>
                Taux horaire :
                <strong class="text-gray-800 dark:text-gray-200">
                    {{ client.hourly_rate !== null ? client.hourly_rate + ' €/h' : 'Non défini' }}
                </strong>
            </span>
            <span class="flex items-center gap-1">
                <Briefcase class="h-3 w-3" />
                {{ client.projects_count ?? 0 }} projet(s)
            </span>
        </div>
    </div>
</template>
