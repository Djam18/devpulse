<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowRight, Sparkles } from 'lucide-vue-next';
import { useI18n } from '@/composables/useI18n';

const { t } = useI18n();
</script>

<template>
    <section class="py-20 border-t border-appborder-light bg-surface-light-2/60 dark:border-appborder-dark dark:bg-surface-dark-2/30">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 text-center">
            <div class="relative overflow-hidden rounded-3xl bg-plum-950 p-8 sm:p-14 text-white shadow-2xl ring-1 ring-plum-800">
                <div class="relative z-10 space-y-4">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-peach-500/20 px-3 py-1 text-xs font-bold text-peach-300 backdrop-blur-md">
                        <Sparkles class="h-3.5 w-3.5" />
                        <span>{{ t('welcome.cta.badge') }}</span>
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-black tracking-tight">
                        {{ t('welcome.cta.heading') }}
                    </h2>
                    <p class="mx-auto max-w-xl text-sm sm:text-base text-purple-200">
                        {{ t('welcome.cta.description') }}
                    </p>
                    <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                        <Link
                            :href="route('register')"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl bg-peach-500 px-7 py-4 text-base font-bold text-gray-950 shadow-lg shadow-peach-500/25 transition-all hover:bg-peach-400 hover:scale-[1.02] active:scale-[0.98]"
                        >
                            <span>{{ t('welcome.cta.freeAccount') }}</span>
                            <ArrowRight class="h-5 w-5" />
                        </Link>
                        <Link
                            :href="route('login')"
                            class="w-full sm:w-auto inline-flex items-center justify-center rounded-2xl border border-white/20 bg-white/10 px-6 py-4 text-base font-semibold text-white backdrop-blur-md transition-all hover:bg-white/20"
                        >
                            <span>{{ t('welcome.cta.login') }}</span>
                        </Link>
                    </div>
                </div>

                <!-- Ambient decorative glow -->
                <div class="absolute -bottom-16 -right-16 h-72 w-72 rounded-full bg-peach-500/20 blur-3xl pointer-events-none"></div>
                <div class="absolute -top-16 -left-16 h-72 w-72 rounded-full bg-plum-500/30 blur-3xl pointer-events-none"></div>
            </div>
        </div>
    </section>
</template>
