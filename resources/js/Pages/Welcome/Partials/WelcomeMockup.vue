<script setup lang="ts">
import { Clock, CheckCircle2, Square } from 'lucide-vue-next';
</script>

<template>
    <div class="mx-auto mt-14 max-w-5xl rounded-3xl border border-appborder-light bg-surface-light-2 p-3 shadow-2xl sm:p-5 ring-1 ring-black/5 dark:border-appborder-dark dark:bg-surface-dark-2/60 dark:ring-white/5">
        <div class="overflow-hidden rounded-2xl border border-appborder-light bg-white shadow-sm dark:border-appborder-dark dark:bg-surface-dark">
            <!-- Mockup Window Bar -->
            <div class="flex items-center justify-between border-b border-appborder-light bg-surface-light-2/70 px-4 py-3 dark:border-appborder-dark dark:bg-surface-dark-2/70">
                <div class="flex items-center gap-2">
                    <span class="h-3 w-3 rounded-full bg-rose-400"></span>
                    <span class="h-3 w-3 rounded-full bg-amber-400"></span>
                    <span class="h-3 w-3 rounded-full bg-emerald-400"></span>
                    <span class="ml-2 font-mono text-xs text-gray-400">app.devpulse.io/tasks</span>
                </div>
                <div class="flex items-center gap-2 text-xs font-semibold text-plum-700 dark:text-plum-300">
                    <span class="inline-block h-2 w-2 rounded-full bg-peach-500 animate-ping"></span>
                    <span>Session active</span>
                </div>
            </div>

            <!-- Mockup Body Content -->
            <div class="p-4 sm:p-6 space-y-6 text-left">
                <!-- Mockup Active Timer Bar -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 rounded-2xl border border-peach-300/60 bg-peach-50/50 p-3 shadow-xs dark:border-peach-900/40 dark:bg-peach-950/20">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-peach-100 text-peach-700 dark:bg-peach-900/60 dark:text-peach-300">
                            <Clock class="h-4 w-4" />
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="rounded bg-plum-100 px-2 py-0.5 text-[10px] font-bold text-plum-800 dark:bg-plum-900/60 dark:text-plum-300">DevPulse API</span>
                                <p class="text-sm font-bold text-gray-900 dark:text-gray-100">Optimisation des requêtes PostgreSQL & Indexation</p>
                            </div>
                            <p class="text-xs text-gray-500">Tâche associée : #DP-104 · Sprint actif</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="rounded-xl bg-peach-100/80 px-3.5 py-1.5 font-mono text-sm font-extrabold text-peach-800 dark:bg-peach-950/60 dark:text-peach-300">
                            01:42:18
                        </div>
                        <div class="inline-flex items-center gap-1.5 rounded-xl bg-peach-700 px-3.5 py-1.5 text-xs font-bold text-white shadow-xs dark:bg-peach-400 dark:text-gray-950">
                            <Square class="h-3 w-3 fill-current" />
                            <span>STOP</span>
                        </div>
                    </div>
                </div>

                <!-- Mockup 3-Column Kanban Board Preview -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Column 1: A Faire -->
                    <div class="rounded-2xl border border-appborder-light bg-surface-light-2 p-3.5 dark:border-appborder-dark dark:bg-surface-dark-2/40">
                        <div class="flex items-center justify-between mb-3 text-xs font-bold">
                            <span class="flex items-center gap-1.5 text-gray-700 dark:text-gray-300">
                                <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                À Faire (2)
                            </span>
                        </div>
                        <div class="space-y-2.5">
                            <div class="rounded-xl border border-appborder-light bg-white p-3 shadow-xs dark:border-appborder-dark dark:bg-surface-dark">
                                <div class="flex items-center justify-between text-[10px]">
                                    <span class="rounded border border-plum-200 bg-plum-50 px-1.5 py-0.5 font-bold text-plum-700 dark:border-plum-800 dark:bg-plum-950/50 dark:text-plum-300">HIGH</span>
                                    <span class="text-gray-400 font-mono">🍅 0/3</span>
                                </div>
                                <p class="mt-2 text-xs font-bold text-gray-800 dark:text-gray-200">Intégration Authentification Passkeys (WebAuthn)</p>
                                <div class="mt-2.5 flex items-center justify-between pt-2 border-t border-gray-100 dark:border-gray-800 text-[11px] text-gray-400">
                                    <span>Client : Acme Corp</span>
                                    <span class="text-plum-700 dark:text-plum-400 font-bold">Démarrer →</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Column 2: En Cours -->
                    <div class="rounded-2xl border border-appborder-light bg-surface-light-2 p-3.5 dark:border-appborder-dark dark:bg-surface-dark-2/40">
                        <div class="flex items-center justify-between mb-3 text-xs font-bold">
                            <span class="flex items-center gap-1.5 text-peach-700 dark:text-peach-400">
                                <span class="h-2 w-2 rounded-full bg-peach-600"></span>
                                En Cours (1)
                            </span>
                        </div>
                        <div class="space-y-2.5">
                            <div class="rounded-xl border border-peach-300/80 bg-white p-3 shadow-sm ring-1 ring-peach-500/20 dark:border-peach-800 dark:bg-surface-dark">
                                <div class="flex items-center justify-between text-[10px]">
                                    <span class="rounded border border-rose-200 bg-rose-50 px-1.5 py-0.5 font-bold text-rose-700 dark:border-rose-900 dark:bg-rose-950/50 dark:text-rose-400">URGENT</span>
                                    <span class="text-peach-700 font-mono font-bold dark:text-peach-300">🍅 2/4</span>
                                </div>
                                <p class="mt-2 text-xs font-bold text-gray-900 dark:text-gray-100">Optimisation des requêtes PostgreSQL & Indexation</p>
                                <div class="mt-2.5 flex items-center justify-between pt-2 border-t border-gray-100 dark:border-gray-800 text-[11px]">
                                    <span class="text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1">
                                        <Clock class="h-3 w-3" /> En suivi
                                    </span>
                                    <span class="font-bold text-peach-700 dark:text-peach-400">Terminer ✓</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Column 3: Terminé -->
                    <div class="rounded-2xl border border-appborder-light bg-surface-light-2 p-3.5 dark:border-appborder-dark dark:bg-surface-dark-2/40">
                        <div class="flex items-center justify-between mb-3 text-xs font-bold">
                            <span class="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                Terminé (4)
                            </span>
                        </div>
                        <div class="space-y-2.5">
                            <div class="rounded-xl border border-appborder-light bg-white p-3 opacity-90 shadow-xs dark:border-appborder-dark dark:bg-surface-dark">
                                <div class="flex items-center justify-between text-[10px]">
                                    <span class="rounded border border-gray-200 bg-gray-50 px-1.5 py-0.5 font-bold text-gray-600 dark:border-gray-800 dark:bg-gray-800 dark:text-gray-400">DONE</span>
                                    <span class="text-gray-400 font-mono">🍅 4/4</span>
                                </div>
                                <p class="mt-2 text-xs font-semibold text-gray-700 line-through dark:text-gray-300">Refonte charte graphique « Plum & Peach »</p>
                                <div class="mt-2.5 flex items-center justify-between pt-2 border-t border-gray-100 dark:border-gray-800 text-[11px] text-gray-400">
                                    <span>100% complété</span>
                                    <CheckCircle2 class="h-3.5 w-3.5 text-emerald-600" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
