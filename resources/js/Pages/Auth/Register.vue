<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

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
        <Head title="Créer un compte · DevPulse" />

        <div class="mb-6 text-center">
            <h1 class="text-2xl font-black tracking-tight text-gray-900 dark:text-gray-100">
                Créer votre compte
            </h1>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Rejoignez la suite de productivité pour développeurs & freelances.
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
        <div>
            <a
                :href="route('auth.github.redirect')"
                class="flex w-full items-center justify-center gap-3 rounded-2xl border border-appborder-light bg-surface-light-2 px-4 py-3 text-sm font-bold text-gray-800 shadow-xs transition-all hover:border-plum-300 hover:bg-white hover:text-plum-700 hover:scale-[1.01] active:scale-[0.99] dark:border-appborder-dark dark:bg-surface-dark-2 dark:text-gray-100 dark:hover:border-plum-800"
            >
                <!-- GitHub Icon -->
                <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24">
                    <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                    />
                </svg>
                <span>S'inscrire avec GitHub</span>
            </a>
        </div>

        <!-- Divider -->
        <div class="my-6 flex items-center justify-between">
            <span class="w-1/5 border-b border-appborder-light dark:border-appborder-dark lg:w-1/4"></span>
            <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                ou avec votre e-mail
            </span>
            <span class="w-1/5 border-b border-appborder-light dark:border-appborder-dark lg:w-1/4"></span>
        </div>

        <!-- Classical Form -->
        <form class="space-y-4" @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Nom complet" />
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full rounded-xl border border-appborder-light px-3.5 py-2.5 shadow-xs focus:border-plum-600 focus:ring-plum-600 dark:border-appborder-dark dark:bg-surface-dark-2"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Ada Lovelace"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Adresse e-mail" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full rounded-xl border border-appborder-light px-3.5 py-2.5 shadow-xs focus:border-plum-600 focus:ring-plum-600 dark:border-appborder-dark dark:bg-surface-dark-2"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    placeholder="ada@devpulse.io"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" value="Mot de passe" />
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
                <InputLabel for="password_confirmation" value="Confirmer le mot de passe" />
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
                    Créer mon compte
                </PrimaryButton>
            </div>
        </form>

        <div class="mt-6 text-center text-xs text-gray-500 dark:text-gray-400">
            Déjà inscrit ?
            <Link :href="route('login')" class="font-bold text-plum-700 hover:text-plum-800 dark:text-plum-400">
                Se connecter
            </Link>
        </div>
    </GuestLayout>
</template>
