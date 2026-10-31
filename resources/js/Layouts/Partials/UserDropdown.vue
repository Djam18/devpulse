<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { ChevronDown, LogOut, User } from 'lucide-vue-next';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const page = usePage();

const userName = computed(() => (page.props.auth as any)?.user?.name || 'User');
const userEmail = computed(() => (page.props.auth as any)?.user?.email || '');
const userAvatar = computed(() => (page.props.auth as any)?.user?.avatar || null);
const userInitials = computed(() => {
    const parts = userName.value.split(' ');
    if (parts.length >= 2) {
        return `${parts[0][0]}${parts[1][0]}`.toUpperCase();
    }
    return userName.value.substring(0, 2).toUpperCase();
});
</script>

<template>
    <Dropdown align="right" width="48">
        <template #trigger>
            <button
                type="button"
                class="flex items-center gap-2.5 rounded-xl border border-appborder-light bg-white px-3 py-1.5 text-sm font-medium text-gray-700 shadow-xs transition-all hover:bg-surface-light-2 focus:outline-none focus:ring-2 focus:ring-plum-500 dark:border-appborder-dark dark:bg-surface-dark dark:text-gray-300 dark:hover:bg-surface-dark-2"
            >
                <img
                    v-if="userAvatar"
                    :src="userAvatar"
                    alt="Avatar"
                    class="h-7 w-7 rounded-lg object-cover ring-1 ring-appborder-light dark:ring-appborder-dark"
                />
                <div
                    v-else
                    class="flex h-7 w-7 items-center justify-center rounded-lg bg-plum-700 text-xs font-bold text-white shadow-xs dark:bg-plum-600"
                >
                    {{ userInitials }}
                </div>
                <span class="font-semibold">{{ userName }}</span>
                <ChevronDown class="h-3.5 w-3.5 text-gray-400" />
            </button>
        </template>

        <template #content>
            <div class="px-4 py-2 border-b border-appborder-light dark:border-appborder-dark">
                <p class="text-xs text-gray-400">Signed in as</p>
                <p class="text-xs font-bold text-gray-800 dark:text-gray-200 truncate">
                    {{ userEmail }}
                </p>
            </div>
            <DropdownLink :href="route('profile.edit')" class="flex items-center gap-2">
                <User class="h-4 w-4 text-gray-400" />
                <span>Profile</span>
            </DropdownLink>
            <DropdownLink :href="route('logout')" method="post" as="button" class="flex items-center gap-2 text-rose-600 dark:text-rose-400">
                <LogOut class="h-4 w-4" />
                <span>Log Out</span>
            </DropdownLink>
        </template>
    </Dropdown>
</template>
