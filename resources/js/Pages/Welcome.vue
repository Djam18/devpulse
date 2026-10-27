<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowRight,
    CheckCircle2,
    Clock,
    Code2,
    KanbanSquare,
    Laptop,
    Moon,
    Play,
    ShieldCheck,
    Sparkles,
    Square,
    Sun,
    Timer,
    Zap,
} from 'lucide-vue-next';

import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    laravelVersion?: string;
    phpVersion?: string;
}>();

const isDark = ref(false);

function toggleTheme(): void {
    isDark.value = !isDark.value;
    if (isDark.value) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
}

onMounted(() => {
    const savedTheme = localStorage.getItem('theme');
    // Default to pure white light mode
    if (savedTheme === 'dark') {
        isDark.value = true;
        document.documentElement.classList.add('dark');
    } else {
        isDark.value = false;
        document.documentElement.classList.remove('dark');
    }
});
</script>

<template>
    <Head title="DevPulse · La suite de productivité pour développeurs & freelances" />

    <div class="min-h-screen bg-white font-sans text-gray-900 selection:bg-plum-500 selection:text-white dark:bg-appbg-dark dark:text-gray-100">
        <!-- Ambient decorative background glow -->
        <div class="pointer-events-none fixed inset-0 overflow-hidden">
            <div class="absolute -top-40 right-1/4 h-[500px] w-[500px] rounded-full bg-plum-500/10 blur-[120px] dark:bg-plum-600/10"></div>
            <div class="absolute top-1/3 -left-40 h-[450px] w-[450px] rounded-full bg-peach-500/10 blur-[120px] dark:bg-peach-600/10"></div>
        </div>

        <!-- Sticky Navigation Header -->
        <header class="sticky top-0 z-50 border-b border-appborder-light bg-white/90 backdrop-blur-md dark:border-appborder-dark dark:bg-surface-dark/90">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <!-- Brand Logo -->
                <Link href="/" class="flex items-center">
                    <ApplicationLogo with-text />
                </Link>

                <!-- Center Nav Links -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-gray-600 dark:text-gray-300">
                    <a href="#features" class="transition-colors hover:text-plum-700 dark:hover:text-plum-300">Fonctionnalités</a>
                    <a href="#workflow" class="transition-colors hover:text-plum-700 dark:hover:text-plum-300">Workflow</a>
                    <a href="#metrics" class="transition-colors hover:text-plum-700 dark:hover:text-plum-300">Métriques</a>
                    <a href="#architecture" class="transition-colors hover:text-plum-700 dark:hover:text-plum-300">Architecture</a>
                </nav>

                <!-- Actions / Auth Links & Theme Switcher -->
                <div class="flex items-center gap-3">
                    <!-- Light / Dark Mode Toggle -->
                    <button
                        type="button"
                        :title="isDark ? 'Passer au thème clair' : 'Passer au thème sombre doux'"
                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-appborder-light bg-white text-gray-500 shadow-xs transition-all hover:bg-surface-light-2 hover:text-plum-700 focus:outline-none focus:ring-2 focus:ring-plum-500 dark:border-appborder-dark dark:bg-surface-dark dark:text-gray-400 dark:hover:text-plum-300"
                        @click="toggleTheme"
                    >
                        <Sun v-if="isDark" class="h-4 w-4" />
                        <Moon v-else class="h-4 w-4" />
                    </button>

                    <template v-if="$page.props.auth?.user">
                        <Link
                            :href="route('dashboard')"
                            class="inline-flex items-center gap-2 rounded-xl bg-plum-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-plum-800 dark:bg-plum-400 dark:text-gray-950 dark:hover:bg-plum-300"
                        >
                            <Laptop class="h-4 w-4" />
                            <span>Accéder au Dashboard</span>
                        </Link>
                    </template>

                    <template v-else>
                        <Link
                            v-if="canLogin"
                            :href="route('login')"
                            class="hidden sm:inline-flex rounded-xl px-3.5 py-2 text-sm font-semibold text-gray-700 transition-colors hover:bg-surface-light-2 hover:text-plum-700 dark:text-gray-300 dark:hover:bg-surface-dark dark:hover:text-plum-300"
                        >
                            Connexion
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="inline-flex items-center gap-2 rounded-xl bg-plum-700 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-plum-700/20 transition-all hover:bg-plum-800 hover:scale-[1.02] active:scale-[0.98] dark:bg-plum-400 dark:text-gray-950 dark:hover:bg-plum-300"
                        >
                            <span>Démarrer</span>
                            <ArrowRight class="h-4 w-4" />
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <main class="relative z-10">
            <!-- Hero Section -->
            <section class="relative overflow-hidden pt-12 pb-20 sm:pt-20 sm:pb-28">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
                    <!-- Pill Tagline -->
                    <div class="inline-flex items-center gap-2 rounded-full border border-plum-200 bg-plum-50/70 px-4 py-1.5 text-xs font-bold text-plum-800 shadow-xs dark:border-plum-900/60 dark:bg-plum-950/40 dark:text-plum-300">
                        <Sparkles class="h-3.5 w-3.5 text-plum-600 dark:text-plum-400" />
                        <span>DevPulse v1.0 · Conçu pour les développeurs & freelances exigeants</span>
                    </div>

                    <!-- Main Catchy Title -->
                    <h1 class="mx-auto mt-6 max-w-4xl text-4xl font-black tracking-tight sm:text-6xl sm:leading-[1.15]">
                        Arrêtez de gaspiller votre temps de code dans des outils
                        <span class="bg-gradient-to-r from-plum-700 via-purple-600 to-peach-600 bg-clip-text text-transparent dark:from-plum-400 dark:via-purple-300 dark:to-peach-300">
                            lents et surchargés
                        </span>.
                    </h1>

                    <!-- Subtitle -->
                    <p class="mx-auto mt-6 max-w-2xl text-base text-gray-600 sm:text-lg dark:text-gray-300 leading-relaxed">
                        DevPulse unifie le <strong>Time Tracking en temps réel</strong>, un <strong>tableau Kanban réactif</strong> et le focus <strong>Pomodoro</strong> dans une interface fluide, ultra-rapide et sans friction.
                    </p>

                    <!-- Hero Call-to-actions -->
                    <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a
                            :href="route('auth.github.redirect')"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 rounded-2xl bg-gray-900 px-6 py-3.5 text-base font-bold text-white shadow-xl shadow-black/10 transition-all hover:bg-black hover:scale-[1.02] active:scale-[0.98] dark:bg-white dark:text-gray-950 dark:hover:bg-gray-100"
                        >
                            <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24">
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                                />
                            </svg>
                            <span>Démarrer avec GitHub</span>
                        </a>

                        <Link
                            :href="route('register')"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl border border-appborder-light bg-white px-6 py-3.5 text-base font-semibold text-gray-700 shadow-xs transition-all hover:border-plum-300 hover:bg-surface-light-2 hover:text-plum-700 dark:border-appborder-dark dark:bg-surface-dark dark:text-gray-300 dark:hover:bg-surface-dark-2 dark:hover:text-plum-300"
                        >
                            <span>Par e-mail</span>
                            <ArrowRight class="h-4 w-4" />
                        </Link>
                    </div>

                    <!-- Proof Metrics Counter -->
                    <div id="metrics" class="mx-auto mt-14 grid max-w-3xl grid-cols-2 gap-4 sm:grid-cols-4 pt-8 border-t border-appborder-light dark:border-appborder-dark">
                        <div class="space-y-1">
                            <p class="text-2xl sm:text-3xl font-black text-plum-700 dark:text-plum-400">+35%</p>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Temps en Deep Work</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-2xl sm:text-3xl font-black text-peach-700 dark:text-peach-400">&lt; 100ms</p>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Latence des actions</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-2xl sm:text-3xl font-black text-emerald-600 dark:text-emerald-400">0 friction</p>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Clavier + 1-clic start</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white">100%</p>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Souverain & Sécurisé</p>
                        </div>
                    </div>

                    <!-- Interactive Application Showcase Mockup -->
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
                </div>
            </section>

            <!-- Features Section -->
            <section id="features" class="py-20 border-t border-appborder-light bg-surface-light-2/50 dark:border-appborder-dark dark:bg-surface-dark-2/20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-3xl mx-auto space-y-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-plum-700 dark:text-plum-400">Conçu par des développeurs, pour des développeurs</span>
                        <h2 class="text-3xl font-black tracking-tight sm:text-4xl text-gray-900 dark:text-white">
                            Trois piliers pour libérer votre bande passante mentale
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Fini les applications lourdes en Electron qui consomment 2 Go de RAM pour un simple chronomètre.
                        </p>
                    </div>

                    <div class="mt-14 grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Pillar 1 -->
                        <div class="rounded-2xl border border-appborder-light bg-white p-7 shadow-xs transition-all hover:border-plum-300 hover:shadow-lg dark:border-appborder-dark dark:bg-surface-dark dark:hover:border-plum-800">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-plum-100 text-plum-700 dark:bg-plum-950/60 dark:text-plum-300">
                                <Clock class="h-6 w-6" />
                            </div>
                            <h3 class="mt-5 text-lg font-bold text-gray-900 dark:text-white">Time Tracking Instantané</h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                                Lancez un chrono d'une frappe au clavier ou d'un clic sur n'importe quelle carte. La barre reste toujours accessible sans changer d'onglet.
                            </p>
                            <ul class="mt-4 space-y-2 text-xs text-gray-600 dark:text-gray-400">
                                <li class="flex items-center gap-2">
                                    <CheckCircle2 class="h-4 w-4 text-plum-600" />
                                    <span>Calcul au centième & facturation client</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <CheckCircle2 class="h-4 w-4 text-plum-600" />
                                    <span>Détection automatique du projet actif</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Pillar 2 -->
                        <div class="rounded-2xl border border-appborder-light bg-white p-7 shadow-xs transition-all hover:border-peach-300 hover:shadow-lg dark:border-appborder-dark dark:bg-surface-dark dark:hover:border-peach-800">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-peach-100 text-peach-700 dark:bg-peach-950/60 dark:text-peach-300">
                                <KanbanSquare class="h-6 w-6" />
                            </div>
                            <h3 class="mt-5 text-lg font-bold text-gray-900 dark:text-white">Kanban Sprint Réactif</h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                                Organisez votre backlog en 3 flux simples (To Do, In Progress, Done) avec priorités visuelles immédiates et filtre instantané par projet.
                            </p>
                            <ul class="mt-4 space-y-2 text-xs text-gray-600 dark:text-gray-400">
                                <li class="flex items-center gap-2">
                                    <CheckCircle2 class="h-4 w-4 text-peach-600" />
                                    <span>Mise à jour sans rechargement (Inertia + Pinia)</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <CheckCircle2 class="h-4 w-4 text-peach-600" />
                                    <span>Déplacement direct d'une étape à l'autre</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Pillar 3 -->
                        <div class="rounded-2xl border border-appborder-light bg-white p-7 shadow-xs transition-all hover:border-emerald-300 hover:shadow-lg dark:border-appborder-dark dark:bg-surface-dark dark:hover:border-emerald-800">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                                <Timer class="h-6 w-6" />
                            </div>
                            <h3 class="mt-5 text-lg font-bold text-gray-900 dark:text-white">Pomodoro & Anti-Burnout</h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                                Découpez les gros chantiers techniques en sessions intenses de 25 minutes. Évaluez le coût réel en temps avant de livrer au client.
                            </p>
                            <ul class="mt-4 space-y-2 text-xs text-gray-600 dark:text-gray-400">
                                <li class="flex items-center gap-2">
                                    <CheckCircle2 class="h-4 w-4 text-emerald-600" />
                                    <span>Compteur de tomates estimées vs réalisées</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <CheckCircle2 class="h-4 w-4 text-emerald-600" />
                                    <span>Protection contre le multitâche destructeur</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Technical Architecture & Developer Standards -->
            <section id="architecture" class="py-20 bg-white dark:bg-appbg-dark">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-plum-700 dark:text-plum-400">Excellence technique</span>
                            <h2 class="mt-2 text-3xl font-black tracking-tight sm:text-4xl text-gray-900 dark:text-white">
                                Une architecture robuste taillée pour les projets critiques
                            </h2>
                            <p class="mt-4 text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                                DevPulse n'est pas un énième wrapper généré à la volée. Le projet respecte les plus hauts standards d'architecture logicielle : Single Responsibility, Actions réutilisables, DTO typés et tests automatisés.
                            </p>

                            <div class="mt-8 space-y-4">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-plum-100 text-plum-700 dark:bg-plum-950 dark:text-plum-300">
                                        <Code2 class="h-4 w-4" />
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">API REST v1 versionnée & documentée</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Endpoints sécurisés par Laravel Sanctum avec documentation interactive OpenAPI / Swagger intégrée.</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-peach-100 text-peach-700 dark:bg-peach-950 dark:text-peach-300">
                                        <Zap class="h-4 w-4" />
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">Inertia.js & TypeScript de bout en bout</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Expérience SPA sans réécriture d'API superflue, typage strict des contrats de données.</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3">
                                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">
                                        <ShieldCheck class="h-4 w-4" />
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">Base PostgreSQL 17 optimisée</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Indexation des clés étrangères, requêtes Eloquent protégées contre le piège N+1 et intégrité relationnelle stricte.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Visual Code Card -->
                        <div class="rounded-3xl border border-appborder-light bg-surface-light-2 p-5 shadow-xl dark:border-appborder-dark dark:bg-surface-dark-2/60">
                            <div class="rounded-2xl border border-appborder-light bg-[#16131F] p-5 font-mono text-xs text-purple-200 shadow-inner">
                                <div class="flex items-center justify-between border-b border-purple-900/40 pb-3 text-[11px] text-purple-400">
                                    <span>DevPulse\Actions\StartTimerAction.php</span>
                                    <span>PHP 8.3 Strict</span>
                                </div>
                                <pre class="mt-4 overflow-x-auto text-[11px] leading-relaxed text-purple-100"><code><span class="text-plum-400">final readonly class</span> <span class="text-amber-300">StartTimerAction</span>
{
    <span class="text-plum-400">public function</span> <span class="text-blue-400">execute</span>(
        User <span class="text-peach-300">$user</span>,
        TimerData <span class="text-peach-300">$data</span>
    ): TimeEntry {
        <span class="text-gray-400">// Stop ongoing timer in atomic transaction</span>
        <span class="text-peach-300">$this</span>-&gt;stopOngoingTimer(<span class="text-peach-300">$user</span>);

        <span class="text-plum-400">return</span> TimeEntry::<span class="text-blue-400">create</span>([
            <span class="text-emerald-300">'user_id'</span>     =&gt; <span class="text-peach-300">$user</span>-&gt;id,
            <span class="text-emerald-300">'started_at'</span>  =&gt; now(),
            <span class="text-emerald-300">'description'</span> =&gt; <span class="text-peach-300">$data</span>-&gt;description,
            <span class="text-emerald-300">'project_id'</span>  =&gt; <span class="text-peach-300">$data</span>-&gt;projectId,
        ]);
    }
}</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Final High-Converting CTA Banner -->
            <section class="py-20 border-t border-appborder-light bg-surface-light-2/60 dark:border-appborder-dark dark:bg-surface-dark-2/30">
                <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 text-center">
                    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-plum-950 via-purple-900 to-slate-900 p-8 sm:p-14 text-white shadow-2xl ring-1 ring-white/10">
                        <div class="relative z-10 space-y-4">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-peach-500/20 px-3 py-1 text-xs font-bold text-peach-300 backdrop-blur-md">
                                <Sparkles class="h-3.5 w-3.5" />
                                <span>Installation en 30 secondes chrono</span>
                            </span>
                            <h2 class="text-3xl sm:text-4xl font-black tracking-tight">
                                Prêt à maximiser votre productivité de dev ?
                            </h2>
                            <p class="mx-auto max-w-xl text-sm sm:text-base text-purple-200">
                                Rejoignez une suite conçue spécifiquement pour vous faire gagner des heures de concentration chaque semaine.
                            </p>
                            <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                                <Link
                                    :href="route('register')"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl bg-peach-500 px-7 py-4 text-base font-bold text-gray-950 shadow-lg shadow-peach-500/25 transition-all hover:bg-peach-400 hover:scale-[1.02] active:scale-[0.98]"
                                >
                                    <span>Créer mon compte gratuit</span>
                                    <ArrowRight class="h-5 w-5" />
                                </Link>
                                <Link
                                    :href="route('login')"
                                    class="w-full sm:w-auto inline-flex items-center justify-center rounded-2xl border border-white/20 bg-white/10 px-6 py-4 text-base font-semibold text-white backdrop-blur-md transition-all hover:bg-white/20"
                                >
                                    <span>Se connecter</span>
                                </Link>
                            </div>
                        </div>

                        <!-- Ambient decorative glow -->
                        <div class="absolute -bottom-16 -right-16 h-72 w-72 rounded-full bg-peach-500/20 blur-3xl pointer-events-none"></div>
                        <div class="absolute -top-16 -left-16 h-72 w-72 rounded-full bg-plum-500/30 blur-3xl pointer-events-none"></div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="border-t border-appborder-light bg-white py-10 dark:border-appborder-dark dark:bg-surface-dark">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center">
                    <ApplicationLogo with-text />
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    &copy; 2026 DevPulse · Suite de Productivité pour Développeurs & Freelances. Construit avec Laravel 11, Vue 3 & PostgreSQL.
                </p>
                <div class="flex items-center gap-4 text-xs font-medium text-gray-500 dark:text-gray-400">
                    <a href="#features" class="hover:text-plum-700 dark:hover:text-plum-300">Fonctionnalités</a>
                    <a href="/docs/api" target="_blank" class="hover:text-plum-700 dark:hover:text-plum-300">Doc API</a>
                    <Link :href="route('login')" class="hover:text-plum-700 dark:hover:text-plum-300">Connexion</Link>
                </div>
            </div>
        </footer>
    </div>
</template>
