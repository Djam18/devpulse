<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowRight, Sparkles } from 'lucide-vue-next';
import WelcomeMockup from '@/Pages/Welcome/Partials/WelcomeMockup.vue';
import { useI18n } from '@/composables/useI18n';

const { t } = useI18n();
</script>

<template>
    <section class="relative overflow-hidden pt-12 pb-20 sm:pt-20 sm:pb-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <!-- Pill Tagline -->
            <div class="inline-flex items-center gap-2 rounded-full border border-plum-200 bg-plum-50/70 px-4 py-1.5 text-xs font-bold text-plum-800 shadow-xs dark:border-plum-900/60 dark:bg-plum-950/40 dark:text-plum-300">
                <Sparkles class="h-3.5 w-3.5 text-plum-600 dark:text-plum-400" />
                <span>{{ t('welcome.hero.tagline') }}</span>
            </div>

            <!-- Main Catchy Title -->
            <h1 class="mx-auto mt-6 max-w-4xl text-4xl font-black tracking-tight sm:text-6xl sm:leading-[1.15]">
                {{ t('welcome.hero.titleMain') }}
                <span class="text-plum-700 dark:text-plum-400">
                    {{ t('welcome.hero.titleHighlight') }}
                </span>
            </h1>

            <!-- Subtitle -->
            <p class="mx-auto mt-6 max-w-2xl text-base text-gray-600 sm:text-lg dark:text-gray-300 leading-relaxed">
                {{ t('welcome.hero.subtitle') }}
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
                    <span>{{ t('welcome.hero.startGithub') }}</span>
                </a>

                <Link
                    :href="route('register')"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl border border-appborder-light bg-white px-6 py-3.5 text-base font-semibold text-gray-700 shadow-xs transition-all hover:border-plum-300 hover:bg-surface-light-2 hover:text-plum-700 dark:border-appborder-dark dark:bg-surface-dark dark:text-gray-300 dark:hover:bg-surface-dark-2 dark:hover:text-plum-300"
                >
                    <span>{{ t('welcome.hero.startEmail') }}</span>
                    <ArrowRight class="h-4 w-4" />
                </Link>
            </div>

            <!-- Proof Metrics Counter -->
            <div id="metrics" class="mx-auto mt-14 grid max-w-3xl grid-cols-2 gap-4 sm:grid-cols-4 pt-8 border-t border-appborder-light dark:border-appborder-dark">
                <div class="space-y-1">
                    <p class="text-2xl sm:text-3xl font-black text-plum-700 dark:text-plum-400">+35%</p>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ t('welcome.metrics.deepWork') }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-2xl sm:text-3xl font-black text-peach-700 dark:text-peach-400">&lt; 100ms</p>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ t('welcome.metrics.latency') }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-2xl sm:text-3xl font-black text-emerald-600 dark:text-emerald-400">0 friction</p>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ t('welcome.metrics.friction') }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white">100%</p>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ t('welcome.metrics.security') }}</p>
                </div>
            </div>

            <!-- Interactive Application Showcase Mockup -->
            <WelcomeMockup />
        </div>
    </section>
</template>
