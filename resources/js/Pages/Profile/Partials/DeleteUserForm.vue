<script setup lang="ts">
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useI18n } from '@/composables/useI18n';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const { t } = useI18n();

const confirmingUserDeletion = ref(false);
const passwordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value?.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => {
            form.reset();
        },
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                {{ t('profile.delete.heading') }}
            </h2>

            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                {{ t('profile.delete.description') }}
            </p>
        </header>

        <DangerButton
            class="rounded-2xl bg-rose-600 px-5 py-2.5 text-xs font-bold text-white shadow-md shadow-rose-600/20 hover:bg-rose-700"
            @click="confirmUserDeletion"
        >
            {{ t('profile.delete.deleteButton') }}
        </DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                    {{ t('profile.delete.modalTitle') }}
                </h2>

                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                    {{ t('profile.delete.modalDescription') }}
                </p>

                <div class="mt-6">
                    <InputLabel
                        for="password"
                        value="Password"
                        class="sr-only"
                    />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full rounded-xl border border-appborder-light px-3.5 py-2.5 shadow-xs focus:border-rose-600 focus:ring-rose-600 dark:border-appborder-dark dark:bg-surface-dark-2"
                        :placeholder="t('profile.delete.passwordPlaceholder')"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton class="rounded-xl" @click="closeModal">
                        {{ t('profile.delete.cancelButton') }}
                    </SecondaryButton>

                    <DangerButton
                        class="rounded-xl bg-rose-600 px-4 py-2 text-xs font-bold text-white shadow-md shadow-rose-600/20 hover:bg-rose-700"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        {{ t('profile.delete.confirmDeleteButton') }}
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
