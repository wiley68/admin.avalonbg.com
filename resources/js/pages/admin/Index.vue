<script lang="ts" setup>
import { __ } from '@/composables/useTranslate';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { Head } from '@inertiajs/vue3';

const title = __('Administrative panel');
</script>

<template>
    <Head :title="__('Home')"></Head>

    <DefaultLayout :title="title" icon="mdi-view-dashboard">
        <q-page class="flex-center flex">
            <q-card class="q-pa-md">
                <q-card-section>
                    <div class="text-h6">{{ title }}</div>
                </q-card-section>
            </q-card>
        </q-page>
    </DefaultLayout>
</template>
