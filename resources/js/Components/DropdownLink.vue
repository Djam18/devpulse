<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

withDefaults(
    defineProps<{
        href: string;
        method?: 'get' | 'post' | 'put' | 'patch' | 'delete';
        as?: string;
    }>(),
    {
        method: 'get',
        as: 'a',
    },
);
</script>

<template>
    <Link
        :href="href"
        :method="method"
        :as="as"
        class="block w-full px-4 py-2 text-start text-sm font-medium text-gray-700 transition duration-150 ease-in-out hover:bg-surface-light-2 hover:text-plum-700 focus:outline-none dark:text-gray-300 dark:hover:bg-surface-dark-2 dark:hover:text-plum-300"
    >
        <slot />
    </Link>
</template>
