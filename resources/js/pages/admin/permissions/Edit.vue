<script lang="ts" setup>
import { __ } from '@/composables/useTranslate';
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    permission: object;
}>();

const form = useForm({
    name: props.permission?.name,
});

const onSubmit = () => {
    form.put(route('permissions.update', props.permission.id), {
        onFinish: () => form.reset('name'),
    });
};

const title = __('Edit Permission');
</script>

<template>
    <Head :title="title"></Head>

    <DefaultLayout :title="title" icon="mdi-shield-edit">
        <q-page class="q-pa-none">
            <div class="page-container">
                <div class="body-panel">
                    <div class="scrollable-content">
                        <div class="column flex-center flex-grow">
                            <q-card class="q-pa-md full-width">
                                <q-form class="q-gutter-md">
                                    <q-input
                                        v-model="form.name"
                                        :label="__('Permission Name')"
                                        :hint="__('Enter the name of the permission')"
                                        autofocus
                                        :error="form.hasErrors"
                                        :error-message="form.errors.name"
                                    />
                                </q-form>
                            </q-card>
                        </div>
                    </div>
                </div>
                <div class="footer-panel">
                    <q-btn color="primary" :label="__('Permissions')" flat icon="mdi-menu-left" @click="router.get(route('permissions.index'))" />
                    <q-btn @click.prevent="onSubmit" :label="__('Save')" icon="mdi-content-save" color="primary" />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
