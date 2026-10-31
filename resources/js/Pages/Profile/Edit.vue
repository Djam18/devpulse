<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { useI18n } from '@/composables/useI18n';
import { Head } from '@inertiajs/vue3';

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();

const { t } = useI18n();
</script>

<template>
    <Head :title="t('profile.title')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-bold leading-tight text-gray-900 dark:text-gray-100">
                {{ t('profile.header') }}
            </h2>
        </template>

        <div class="py-10">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="rounded-2xl border border-appborder-light bg-white p-6 shadow-xs sm:p-8 dark:border-appborder-dark dark:bg-surface-dark">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div class="rounded-2xl border border-appborder-light bg-white p-6 shadow-xs sm:p-8 dark:border-appborder-dark dark:bg-surface-dark">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="rounded-2xl border border-appborder-light bg-white p-6 shadow-xs sm:p-8 dark:border-appborder-dark dark:bg-surface-dark">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
