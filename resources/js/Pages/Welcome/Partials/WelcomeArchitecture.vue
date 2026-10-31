<script setup lang="ts">
import { Code2, ShieldCheck, Zap } from 'lucide-vue-next';
import { useI18n } from '@/composables/useI18n';

const { t } = useI18n();
</script>

<template>
    <section id="architecture" class="py-20 bg-white dark:bg-appbg-dark">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-plum-700 dark:text-plum-400">
                        {{ t('welcome.architecture.badge') }}
                    </span>
                    <h2 class="mt-2 text-3xl font-black tracking-tight sm:text-4xl text-gray-900 dark:text-white">
                        {{ t('welcome.architecture.heading') }}
                    </h2>
                    <p class="mt-4 text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        {{ t('welcome.architecture.description') }}
                    </p>

                    <div class="mt-8 space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-plum-100 text-plum-700 dark:bg-plum-950 dark:text-plum-300">
                                <Code2 class="h-4 w-4" />
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ t('welcome.architecture.item1Title') }}
                                </h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ t('welcome.architecture.item1Desc') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-peach-100 text-peach-700 dark:bg-peach-950 dark:text-peach-300">
                                <Zap class="h-4 w-4" />
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ t('welcome.architecture.item2Title') }}
                                </h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ t('welcome.architecture.item2Desc') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">
                                <ShieldCheck class="h-4 w-4" />
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ t('welcome.architecture.item3Title') }}
                                </h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ t('welcome.architecture.item3Desc') }}
                                </p>
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
</template>
