<script setup lang="ts">
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useI18n } from '@/composables/useI18n';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    status?: string;
}>();

const { t } = useI18n();

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head :title="t('auth.verifyEmail.title')" />

        <div class="mb-4 text-center">
            <h1 class="text-xl font-black tracking-tight text-gray-900 dark:text-gray-100">
                {{ t('auth.verifyEmail.heading') }}
            </h1>
        </div>

        <div class="mb-4 text-xs text-gray-600 dark:text-gray-400">
            {{ t('auth.verifyEmail.description') }}
        </div>

        <div
            class="mb-4 rounded-xl bg-emerald-50 p-3 text-xs font-semibold text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300"
            v-if="verificationLinkSent"
        >
            {{ t('auth.verifyEmail.linkSent') }}
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div class="flex items-center justify-between">
                <PrimaryButton
                    class="rounded-2xl bg-plum-700 py-2.5 px-4 text-xs font-bold text-white shadow-md shadow-plum-700/20 hover:bg-plum-800 dark:bg-plum-400 dark:text-gray-950 dark:hover:bg-plum-300"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    {{ t('auth.verifyEmail.resendButton') }}
                </PrimaryButton>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="rounded-xl px-3 py-2 text-xs font-medium text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100"
                >
                    {{ t('auth.verifyEmail.logoutButton') }}
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
