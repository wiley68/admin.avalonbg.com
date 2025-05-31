<script lang="ts" setup>
import LanguageSwitcher from '@/components/LanguageSwitcher.vue';
import { usePermission } from '@/composables/permissions';
import { useAppearance } from '@/composables/useAppearance';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps<{
    version: string;
    appName: string;
}>();

const { hasUser, hasRole } = usePermission();
const { theme } = useAppearance();
</script>

<template>
    <Head title="Начало"></Head>

    <GuestLayout>
        <div class="column absolute-full flex-center flex" style="user-select: none">
            <div class="row absolute-top q-pa-md items-center justify-end">
                <q-btn-toggle
                    v-model="theme"
                    :options="[
                        { label: __('Light'), value: 'light' },
                        { label: __('Dark'), value: 'dark' },
                        { label: __('Systematic'), value: 'system' },
                    ]"
                    toggle-color="primary"
                    push
                    ripple
                />

                <LanguageSwitcher class="q-ml-md" />
            </div>
            <div class="column flex-center flex grow">
                <template v-if="hasUser()">
                    <q-btn
                        v-if="hasRole('admin')"
                        @click="router.get(route('admin.index'))"
                        class="text-primary"
                        icon="dashboard"
                        size="lg"
                        label="Табло"
                    />
                    <q-btn v-else @click="router.get(route('dashboard'))" class="text-primary" icon="dashboard" size="lg" label="Табло" />
                </template>
                <template v-else>
                    <q-btn @click="router.get(route('login'))" class="text-primary" icon="login" size="lg" label="Вход" />
                </template>
                <h1 class="text-weight-medium text-primary">{{ appName }}</h1>
                <p class="text-subtitle1 text-weight-light">Laravel v.{{ props.version }} - {{ props.appName }}</p>
            </div>
        </div>
    </GuestLayout>
</template>
