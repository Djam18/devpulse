<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import OAuthButton from '@/Components/OAuthButton.vue';
import { useI18n } from '@/composables/useI18n';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

const { t } = useI18n();
const page = usePage();

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head :title="t('auth.register.title')" />

        <div class="mb-6 text-center">
            <h1 class="text-2xl font-black tracking-tight text-gray-900 dark:text-gray-100">
                {{ t('auth.register.heading') }}
            </h1>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                {{ t('auth.register.subtitle') }}
            </p>
        </div>

        <!-- GitHub OAuth Error Banner -->
        <div
            v-if="page.props.errors?.github"
            class="mb-4 rounded-xl bg-rose-50 p-3 text-xs font-semibold text-rose-700 dark:bg-rose-950/40 dark:text-rose-300"
        >
            {{ page.props.errors.github }}
        </div>

        <!-- One-Click GitHub Signup Button -->
        <OAuthButton :label="t('auth.register.githubButton')" />

        <!-- Divider -->
        <div class="my-6 flex items-center justify-between">
            <span class="w-1/5 border-b border-appborder-light dark:border-appborder-dark lg:w-1/4"></span>
            <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                {{ t('auth.register.divider') }}
            </span>
            <span class="w-1/5 border-b border-appborder-light dark:border-appborder-dark lg:w-1/4"></span>
        </div>

        <!-- Classical Form -->
        <form class="space-y-4" @submit.prevent="submit">
            <div>
                <InputLabel for="name" :value="t('auth.register.nameLabel')" />
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full rounded-xl border border-appborder-light px-3.5 py-2.5 shadow-xs focus:border-plum-600 focus:ring-plum-600 dark:border-appborder-dark dark:bg-surface-dark-2"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    :placeholder="t('auth.register.namePlaceholder')"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" :value="t('auth.register.emailLabel')" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full rounded-xl border border-appborder-light px-3.5 py-2.5 shadow-xs focus:border-plum-600 focus:ring-plum-600 dark:border-appborder-dark dark:bg-surface-dark-2"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    :placeholder="t('auth.register.emailPlaceholder')"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" :value="t('auth.register.passwordLabel')" />
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
                <InputLabel for="password_confirmation" :value="t('auth.register.passwordConfirmationLabel')" />
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
                    {{ t('auth.register.submitButton') }}
                </PrimaryButton>
            </div>
        </form>

        <div class="mt-6 text-center text-xs text-gray-500 dark:text-gray-400">
            {{ t('auth.register.alreadyRegistered') }}
            <Link :href="route('login')" class="font-bold text-plum-700 hover:text-plum-800 dark:text-plum-400">
                {{ t('auth.register.loginLink') }}
            </Link>
        </div>
    </GuestLayout>
</template>
