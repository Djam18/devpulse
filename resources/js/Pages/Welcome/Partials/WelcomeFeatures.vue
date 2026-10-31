<script setup lang="ts">
import { CheckCircle2, Clock, KanbanSquare, Timer } from 'lucide-vue-next';
import { useI18n } from '@/composables/useI18n';

const { t } = useI18n();
</script>

<template>
    <section id="features" class="py-20 border-t border-appborder-light bg-surface-light-2/50 dark:border-appborder-dark dark:bg-surface-dark-2/20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-plum-700 dark:text-plum-400">
                    {{ t('welcome.features.badge') }}
                </span>
                <h2 class="text-3xl font-black tracking-tight sm:text-4xl text-gray-900 dark:text-white">
                    {{ t('welcome.features.heading') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ t('welcome.features.subheading') }}
                </p>
            </div>

            <div class="mt-14 grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Pillar 1 -->
                <div class="rounded-2xl border border-appborder-light bg-white p-7 shadow-xs transition-all hover:border-plum-300 hover:shadow-lg dark:border-appborder-dark dark:bg-surface-dark dark:hover:border-plum-800">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-plum-100 text-plum-700 dark:bg-plum-950/60 dark:text-plum-300">
                        <Clock class="h-6 w-6" />
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-gray-900 dark:text-white">
                        {{ t('welcome.features.pillar1.title') }}
                    </h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        {{ t('welcome.features.pillar1.desc') }}
                    </p>
                    <ul class="mt-4 space-y-2 text-xs text-gray-600 dark:text-gray-400">
                        <li class="flex items-center gap-2">
                            <CheckCircle2 class="h-4 w-4 text-plum-600" />
                            <span>{{ t('welcome.features.pillar1.point1') }}</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <CheckCircle2 class="h-4 w-4 text-plum-600" />
                            <span>{{ t('welcome.features.pillar1.point2') }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Pillar 2 -->
                <div class="rounded-2xl border border-appborder-light bg-white p-7 shadow-xs transition-all hover:border-peach-300 hover:shadow-lg dark:border-appborder-dark dark:bg-surface-dark dark:hover:border-peach-800">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-peach-100 text-peach-700 dark:bg-peach-950/60 dark:text-peach-300">
                        <KanbanSquare class="h-6 w-6" />
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-gray-900 dark:text-white">
                        {{ t('welcome.features.pillar2.title') }}
                    </h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        {{ t('welcome.features.pillar2.desc') }}
                    </p>
                    <ul class="mt-4 space-y-2 text-xs text-gray-600 dark:text-gray-400">
                        <li class="flex items-center gap-2">
                            <CheckCircle2 class="h-4 w-4 text-peach-600" />
                            <span>{{ t('welcome.features.pillar2.point1') }}</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <CheckCircle2 class="h-4 w-4 text-peach-600" />
                            <span>{{ t('welcome.features.pillar2.point2') }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Pillar 3 -->
                <div class="rounded-2xl border border-appborder-light bg-white p-7 shadow-xs transition-all hover:border-emerald-300 hover:shadow-lg dark:border-appborder-dark dark:bg-surface-dark dark:hover:border-emerald-800">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                        <Timer class="h-6 w-6" />
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-gray-900 dark:text-white">
                        {{ t('welcome.features.pillar3.title') }}
                    </h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        {{ t('welcome.features.pillar3.desc') }}
                    </p>
                    <ul class="mt-4 space-y-2 text-xs text-gray-600 dark:text-gray-400">
                        <li class="flex items-center gap-2">
                            <CheckCircle2 class="h-4 w-4 text-emerald-600" />
                            <span>{{ t('welcome.features.pillar3.point1') }}</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <CheckCircle2 class="h-4 w-4 text-emerald-600" />
                            <span>{{ t('welcome.features.pillar3.point2') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</template>
