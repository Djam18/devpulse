<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-white pt-8 sm:justify-center sm:pt-0 dark:bg-appbg-dark antialiased text-gray-900 dark:text-gray-100"
    >
        <!-- Brand Logo -->
        <div>
            <Link href="/" class="flex items-center">
                <ApplicationLogo with-text />
            </Link>
        </div>

        <!-- Auth Card Container -->
        <div
            class="mt-6 w-full overflow-hidden border border-appborder-light bg-white p-8 shadow-xl sm:max-w-md sm:rounded-3xl dark:border-appborder-dark dark:bg-surface-dark"
        >
            <slot />
        </div>
    </div>
</template>
