<script lang="ts" setup>
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    email: string;
    token: string;
}>();

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const onSubmit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head :title="__('Change password')"></Head>

        <q-page class="column flex-center">
            <q-card class="q-pa-md" style="width: 400px; max-width: 90vw">
                <q-card-section class="q-ma-sm">
                    <q-form @submit="onSubmit" class="q-gutter-md">
                        <q-input
                            v-model="form.email"
                            :label="__('Email *')"
                            :hint="__('Login email')"
                            autocomplete="email"
                            autofocus
                            :error="form.hasErrors"
                            :error-message="form.errors.email"
                        />
                        <q-input
                            v-model="form.password"
                            :label="__('Password *')"
                            type="password"
                            :hint="__('Login password')"
                            :error="form.hasErrors"
                            :error-message="form.errors.password"
                        />
                        <q-input
                            v-model="form.password_confirmation"
                            :label="__('Confirm password')"
                            type="password"
                            :hint="__('Confirm password')"
                            :error="form.hasErrors"
                            :error-message="form.errors.password_confirmation"
                        />
                        <div class="q-mt-lg row items-center justify-end">
                            <q-btn label="Смяна на парола" color="primary" type="submit" class="full-width q-mt-md" />
                        </div>
                    </q-form>
                </q-card-section>
            </q-card>
        </q-page>
    </GuestLayout>
</template>
