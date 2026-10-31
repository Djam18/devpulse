<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useI18n } from '@/composables/useI18n';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    email: string;
    token: string;
}>();

const { t } = useI18n();

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head :title="t('auth.resetPassword.title')" />

        <div class="mb-6 text-center">
            <h1 class="text-xl font-black tracking-tight text-gray-900 dark:text-gray-100">
                {{ t('auth.resetPassword.heading') }}
            </h1>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <InputLabel for="email" :value="t('auth.resetPassword.emailLabel')" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full rounded-xl border border-appborder-light px-3.5 py-2.5 shadow-xs focus:border-plum-600 focus:ring-plum-600 dark:border-appborder-dark dark:bg-surface-dark-2"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" :value="t('auth.resetPassword.passwordLabel')" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full rounded-xl border border-appborder-light px-3.5 py-2.5 shadow-xs focus:border-plum-600 focus:ring-plum-600 dark:border-appborder-dark dark:bg-surface-dark-2"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div>
                <InputLabel for="password_confirmation" :value="t('auth.resetPassword.passwordConfirmationLabel')" />
                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full rounded-xl border border-appborder-light px-3.5 py-2.5 shadow-xs focus:border-plum-600 focus:ring-plum-600 dark:border-appborder-dark dark:bg-surface-dark-2"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="pt-2">
                <PrimaryButton
                    class="w-full justify-center rounded-2xl bg-plum-700 py-3 text-sm font-bold text-white shadow-md shadow-plum-700/20 hover:bg-plum-800 dark:bg-plum-400 dark:text-gray-950 dark:hover:bg-plum-300"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    {{ t('auth.resetPassword.submitButton') }}
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
