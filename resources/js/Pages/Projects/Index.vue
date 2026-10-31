<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Briefcase, Building2 } from 'lucide-vue-next';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import EmptyState from '@/Components/ui/EmptyState.vue';
import MetricTooltip from '@/Components/ui/MetricTooltip.vue';
import ClientCard from '@/features/projects/components/ClientCard.vue';
import ClientModal from '@/features/projects/components/ClientModal.vue';
import ProjectCard from '@/features/projects/components/ProjectCard.vue';
import ProjectModal from '@/features/projects/components/ProjectModal.vue';
import type { Client, ClientPayload, Project, ProjectPayload } from '@/features/projects/types';
import * as projectApi from '@/features/projects/api';

const props = defineProps<{ initialProjects: Project[]; initialClients: Client[] }>();

const activeTab = ref<'projects' | 'clients'>('projects');
const projects = ref<Project[]>([...props.initialProjects]);
const clients = ref<Client[]>([...props.initialClients]);

const showProjectModal = ref(false);
const editingProject = ref<Project | null>(null);
const showClientModal = ref(false);
const editingClient = ref<Client | null>(null);

function openProjectModal(p: Project | null = null): void { editingProject.value = p; showProjectModal.value = true; }
function openClientModal(c: Client | null = null): void { editingClient.value = c; showClientModal.value = true; }

async function saveProject(payload: ProjectPayload): Promise<void> {
    if (editingProject.value) {
        const updated = await projectApi.updateProject(editingProject.value.id, payload);
        const idx = projects.value.findIndex((p) => p.id === updated.id);
        if (idx !== -1) projects.value[idx] = updated;
    } else {
        const created = await projectApi.createProject(payload);
        projects.value.unshift(created);
    }
    showProjectModal.value = false;
}

async function removeProject(id: number): Promise<void> {
    if (!confirm('Supprimer ce projet ?')) return;
    await projectApi.deleteProject(id);
    projects.value = projects.value.filter((p) => p.id !== id);
}

async function saveClient(payload: ClientPayload): Promise<void> {
    if (editingClient.value) {
        const updated = await projectApi.updateClient(editingClient.value.id, payload);
        const idx = clients.value.findIndex((c) => c.id === updated.id);
        if (idx !== -1) clients.value[idx] = updated;
    } else {
        const created = await projectApi.createClient(payload);
        clients.value.push(created);
    }
    showClientModal.value = false;
}

async function removeClient(id: number): Promise<void> {
    if (!confirm('Supprimer ce client ? Les projets rattachés seront détachés.')) return;
    await projectApi.deleteClient(id);
    clients.value = clients.value.filter((c) => c.id !== id);
}
</script>

<template>
    <Head title="Clients & Projets - DevPulse" />
    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-appborder-light dark:border-appborder-dark">
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl font-black tracking-tight text-gray-900 dark:text-gray-100">Clients & Projets</h1>
                        <MetricTooltip text="Associez vos projets à des clients avec un taux horaire pour calculer automatiquement vos revenus et le burn-rate." />
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Configurez vos clients, leurs taux horaires et vos budgets d'heures vendues.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" class="inline-flex items-center gap-1.5 rounded-xl border border-appborder-light bg-white px-3.5 py-2 text-xs font-semibold text-gray-700 shadow-xs hover:bg-surface-light-2 dark:border-appborder-dark dark:bg-surface-dark dark:text-gray-200" @click="openClientModal()">
                        <Building2 class="h-3.5 w-3.5 text-plum-600" /> + Nouveau Client
                    </button>
                    <button type="button" class="inline-flex items-center gap-1.5 rounded-xl bg-plum-700 px-3.5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-plum-800 dark:bg-plum-600" @click="openProjectModal()">
                        <Briefcase class="h-3.5 w-3.5" /> + Nouveau Projet
                    </button>
                </div>
            </div>

            <!-- Tab Switcher -->
            <div class="flex items-center gap-2 my-6">
                <button type="button" :class="['rounded-xl px-4 py-2 text-xs font-bold transition-all', activeTab === 'projects' ? 'bg-plum-50 text-plum-700 dark:bg-plum-950/40 dark:text-plum-300' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400']" @click="activeTab = 'projects'">
                    Projets ({{ projects.length }})
                </button>
                <button type="button" :class="['rounded-xl px-4 py-2 text-xs font-bold transition-all', activeTab === 'clients' ? 'bg-plum-50 text-plum-700 dark:bg-plum-950/40 dark:text-plum-300' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400']" @click="activeTab = 'clients'">
                    Clients ({{ clients.length }})
                </button>
            </div>

            <!-- Tab Transition Container (Idée 2) -->
            <Transition name="fade" mode="out-in">
                <!-- Projects View -->
                <div v-if="activeTab === 'projects'" key="projects">
                    <EmptyState
                        v-if="projects.length === 0"
                        title="Aucun projet pour le moment"
                        description="Créez votre premier projet pour budgétiser vos heures, lier vos tâches et calculer votre rentabilité."
                        action-label="+ Créer mon premier projet"
                        @action="openProjectModal()"
                    >
                        <template #icon>
                            <Briefcase class="h-6 w-6" />
                        </template>
                    </EmptyState>
                    <div v-else class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <ProjectCard v-for="p in projects" :key="p.id" :project="p" @edit="openProjectModal(p)" @delete="removeProject(p.id)" />
                    </div>
                </div>

                <!-- Clients View -->
                <div v-else key="clients">
                    <EmptyState
                        v-if="clients.length === 0"
                        title="Aucun client enregistré"
                        description="Ajoutez un client avec son taux horaire pour calculer automatiquement le chiffre d'affaires généré par votre travail."
                        action-label="+ Ajouter mon premier client"
                        @action="openClientModal()"
                    >
                        <template #icon>
                            <Building2 class="h-6 w-6" />
                        </template>
                    </EmptyState>
                    <div v-else class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <ClientCard v-for="c in clients" :key="c.id" :client="c" @edit="openClientModal(c)" @delete="removeClient(c.id)" />
                    </div>
                </div>
            </Transition>
        </div>

        <ProjectModal :show="showProjectModal" :project="editingProject" :clients="clients" @close="showProjectModal = false" @save="saveProject" />
        <ClientModal :show="showClientModal" :client="editingClient" @close="showClientModal = false" @save="saveClient" />
    </AuthenticatedLayout>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.15s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
