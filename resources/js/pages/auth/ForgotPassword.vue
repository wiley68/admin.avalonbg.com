<script lang="ts" setup>
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});

const onSubmit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head :title="__('Forgotten password')"></Head>

    <GuestLayout>
        <q-page class="column flex-center">
            <div class="q-mb-md text-body2 text-gray">
                {{ __('forgott_text') }}
            </div>

            <div v-if="status" class="q-mb-md text-body2 text-green">
                {{ status }}
            </div>

            <q-card class="q-pa-md" style="width: 400px; max-width: 90vw">
                <q-card-section class="q-ma-sm">
                    <q-form @submit.prevent="onSubmit" class="q-gutter-md">
                        <q-input
                            v-model="form.email"
                            :label="__('Email *')"
                            :hint="__('Login email')"
                            autocomplete="email"
                            :error="form.hasErrors"
                            :error-message="form.errors.email"
                        />
                        <div class="q-mt-lg row items-center justify-end">
                            <q-btn :label="__('Email password reset link')" color="primary" type="submit" class="full-width q-mt-md" />
                        </div>
                    </q-form>
                </q-card-section>
            </q-card>
        </q-page>
    </GuestLayout>
</template>
