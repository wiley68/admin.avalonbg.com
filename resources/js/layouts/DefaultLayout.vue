<script lang="ts" setup>
import { usePermission } from '@/composables/permissions';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps<{
    title: string;
    icon: string;
}>();

const leftDrawerOpen = ref(false);
const { hasRole } = usePermission();

const toggleLeftDrawer = () => {
    leftDrawerOpen.value = !leftDrawerOpen.value;
};
</script>

<template>
    <q-layout view="hHh lpR fFf">
        <q-header bordered class="bg-primary text-white select-none">
            <q-toolbar>
                <q-btn dense flat round icon="mdi-menu" @click="toggleLeftDrawer" class="q-mr-sm" />

                <q-separator dark vertical inset />

                <q-toolbar-title class="flex-none select-none">
                    <q-icon name="mdi-at" size="md"></q-icon>
                    {{ $page.props.app_name }}
                </q-toolbar-title>

                <q-separator dark vertical inset />

                <q-toolbar-title>
                    <div class="row items-center">
                        <q-icon :name="icon" size="md" class="q-mr-sm"></q-icon>
                        {{ title }}
                    </div>
                </q-toolbar-title>

                <q-separator dark vertical inset />

                <q-btn-dropdown stretch flat :label="$page.props.auth.user.name">
                    <q-list style="min-width: 100px">
                        <q-item clickable v-close-popup @click="router.post(route('logout'))">
                            <q-item-section avatar>
                                <q-icon color="negative" name="mdi-close" />
                            </q-item-section>
                            <q-item-section>{{ __('Exit') }}</q-item-section>
                        </q-item>
                    </q-list>
                </q-btn-dropdown>
            </q-toolbar>
        </q-header>

        <q-drawer show-if-above v-model="leftDrawerOpen" side="left" behavior="desktop" bordered>
            <q-list class="full-height column flex">
                <template v-if="hasRole('admin')">
                    <q-item
                        clickable
                        v-close-popup
                        @click="router.get(route('admin.index'))"
                        :active="route().current('admin.index')"
                        class="text-primary"
                        active-class="bg-blue-1"
                    >
                        <q-item-section avatar>
                            <q-icon color="primary" name="mdi-view-dashboard" />
                        </q-item-section>
                        <q-item-section>{{ __('Dashboard') }}</q-item-section>
                    </q-item>
                </template>
                <template v-else>
                    <q-item
                        clickable
                        v-close-popup
                        @click="router.get(route('dashboard'))"
                        :active="route().current('dashboard')"
                        class="text-primary"
                        active-class="bg-blue-1"
                    >
                        <q-item-section avatar>
                            <q-icon color="primary" name="mdi-view-dashboard" />
                        </q-item-section>
                        <q-item-section>{{ __('Dashboard') }}</q-item-section>
                    </q-item>
                </template>

                <q-separator />

                <template v-if="hasRole('admin')">
                    <q-item
                        clickable
                        v-close-popup
                        class="text-primary"
                        active-class="bg-blue-1"
                        @click="router.get(route('users.index'))"
                        :active="route().current('users.index')"
                    >
                        <q-item-section avatar>
                            <q-icon color="primary" name="mdi-account-multiple" />
                        </q-item-section>
                        <q-item-section>{{ __('Users') }}</q-item-section>
                    </q-item>

                    <q-separator />

                    <q-item
                        clickable
                        v-close-popup
                        class="text-primary"
                        active-class="bg-blue-1"
                        @click="router.get(route('roles.index'))"
                        :active="route().current('roles.index')"
                    >
                        <q-item-section avatar>
                            <q-icon color="primary" name="mdi-account-group" />
                        </q-item-section>
                        <q-item-section>{{ __('Roles') }}</q-item-section>
                    </q-item>

                    <q-separator />

                    <q-item
                        clickable
                        v-close-popup
                        class="text-primary"
                        active-class="bg-blue-1"
                        @click="router.get(route('permissions.index'))"
                        :active="route().current('permissions.index')"
                    >
                        <q-item-section avatar>
                            <q-icon color="primary" name="mdi-shield-key" />
                        </q-item-section>
                        <q-item-section>{{ __('Permissions') }}</q-item-section>
                    </q-item>

                    <q-separator />
                </template>

                <q-space />

                <q-separator />

                <q-item clickable v-close-popup class="text-negative" @click="router.post(route('logout'))">
                    <q-item-section avatar>
                        <q-icon color="negative" name="close" />
                    </q-item-section>
                    <q-item-section>{{ __('Exit') }}</q-item-section>
                </q-item>
            </q-list>
        </q-drawer>

        <q-page-container>
            <slot></slot>
        </q-page-container>

        <q-footer bordered class="bg-grey-2 text-grey-10 q-custom-toolbar">
            <q-toolbar class="q-custom-toolbar select-none">
                <q-toolbar-title class="text-subtitle1 text-title text-left"
                    >{{ $page.props.app_name }}: v. {{ $page.props.version }}</q-toolbar-title
                >
                <q-separator vertical />
                <template v-if="$page.props.auth.user.roles.length">
                    <q-toolbar-title
                        v-for="role in $page.props.auth.user.roles"
                        :key="role"
                        class="text-subtitle1 text-title text-left"
                        :class="role === 'admin' ? 'text-red-8' : 'text-gray-10'"
                        >{{ role }}</q-toolbar-title
                    >
                    <q-separator vertical />
                </template>
                <q-toolbar-title class="text-subtitle1 text-title text-left">{{ $page.props.auth.user.name }}</q-toolbar-title>
                <q-separator vertical />
                <q-toolbar-title class="text-subtitle1 text-title text-left">{{ $page.props.auth.user.email }}</q-toolbar-title>
                <q-separator vertical />
            </q-toolbar>
        </q-footer>
    </q-layout>
</template>

<style>
.q-custom-toolbar {
    min-height: 30px !important;
}

.text-title {
    max-width: max-content;
    white-space: nowrap;
}

.page-container {
    display: flex;
    flex-direction: column;
    height: calc(100vh - 82px);
}

.body-panel {
    flex: 1;
    overflow-y: auto;
    border-bottom: 1px solid #e0e0e0;
}

.header-panel {
    display: flex;
    align-items: center;
    width: 100%;
    background-color: #ffffff;
    border-bottom: 1px solid #e0e0e0;
}

.footer-panel {
    height: 48px;
    display: flex;
    gap: 5px;
    align-items: center;
    width: 100%;
    background-color: #ffffff;
    padding-left: 4px;
    padding-right: 4px;
}

.scrollable-content {
    padding: 16px;
}

.q-field__messages > div {
    color: #1976d2;
}

.q-field__messages > div[role='alert'] {
    color: #c10015;
}

.flex-none {
    flex-grow: 0;
    flex-shrink: 0;
    flex-basis: auto;
}

.select-none {
    user-select: none;
}
</style>
