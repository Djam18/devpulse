<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useI18n } from '@/composables/useI18n';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps<{
    mustVerifyEmail?: Boolean;
    status?: String;
}>();

const { t } = useI18n();
const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                {{ t('profile.info.heading') }}
            </h2>

            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                {{ t('profile.info.description') }}
            </p>
        </header>

        <form
            @submit.prevent="form.patch(route('profile.update'))"
            class="mt-6 space-y-4"
        >
            <div>
                <InputLabel for="name" :value="t('profile.info.nameLabel')" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full rounded-xl border border-appborder-light px-3.5 py-2.5 shadow-xs focus:border-plum-600 focus:ring-plum-600 dark:border-appborder-dark dark:bg-surface-dark-2"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" :value="t('profile.info.emailLabel')" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full rounded-xl border border-appborder-light px-3.5 py-2.5 shadow-xs focus:border-plum-600 focus:ring-plum-600 dark:border-appborder-dark dark:bg-surface-dark-2"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-xs text-gray-800 dark:text-gray-200">
                    {{ t('profile.info.unverifiedEmail') }}
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="rounded-md text-xs text-plum-700 underline hover:text-plum-800 dark:text-plum-400"
                    >
                        {{ t('profile.info.resendVerification') }}
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-xs font-semibold text-emerald-600 dark:text-emerald-400"
                >
                    {{ t('profile.info.verificationSent') }}
                </div>
            </div>

            <div class="flex items-center gap-4 pt-2">
                <PrimaryButton
                    class="rounded-2xl bg-plum-700 py-2.5 px-5 text-sm font-bold text-white shadow-md shadow-plum-700/20 hover:bg-plum-800 dark:bg-plum-400 dark:text-gray-950 dark:hover:bg-plum-300"
                    :disabled="form.processing"
                >
                    {{ t('profile.info.saveButton') }}
                </PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-xs font-bold text-emerald-600 dark:text-emerald-400"
                    >
                        {{ t('profile.info.saved') }}
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
