<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useI18n } from '@/composables/useI18n';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
    status?: string;
}>();

const { t } = useI18n();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head :title="t('auth.forgotPassword.title')" />

        <div class="mb-4 text-center">
            <h1 class="text-xl font-black tracking-tight text-gray-900 dark:text-gray-100">
                {{ t('auth.forgotPassword.heading') }}
            </h1>
        </div>

        <div class="mb-4 text-xs text-gray-600 dark:text-gray-400">
            {{ t('auth.forgotPassword.description') }}
        </div>

        <div
            v-if="status"
            class="mb-4 rounded-xl bg-emerald-50 p-3 text-xs font-semibold text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <InputLabel for="email" :value="t('auth.forgotPassword.emailLabel')" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full rounded-xl border border-appborder-light px-3.5 py-2.5 shadow-xs focus:border-plum-600 focus:ring-plum-600 dark:border-appborder-dark dark:bg-surface-dark-2"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="vous@devpulse.io"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="pt-2">
                <PrimaryButton
                    class="w-full justify-center rounded-2xl bg-plum-700 py-3 text-sm font-bold text-white shadow-md shadow-plum-700/20 hover:bg-plum-800 dark:bg-plum-400 dark:text-gray-950 dark:hover:bg-plum-300"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    {{ t('auth.forgotPassword.submitButton') }}
                </PrimaryButton>
            </div>
        </form>

        <div class="mt-6 text-center text-xs text-gray-500 dark:text-gray-400">
            <Link :href="route('login')" class="font-bold text-plum-700 hover:text-plum-800 dark:text-plum-400">
                ← {{ t('auth.forgotPassword.backToLogin') }}
            </Link>
        </div>
    </GuestLayout>
</template>
