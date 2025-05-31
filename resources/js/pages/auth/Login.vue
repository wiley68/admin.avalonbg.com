<script lang="ts" setup>
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const onSubmit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head :title="__('Login')"></Head>

    <GuestLayout>
        <q-page class="column flex-center">
            <div v-if="status" class="q-mb-md text-body2 text-green">
                {{ status }}
            </div>

            <q-card class="q-pa-md" style="width: 400px; max-width: 90vw">
                <q-card-section class="text-h5 q-pa-sm q-ma-sm text-center">
                    {{ __('Login') }}
                </q-card-section>

                <q-card-section class="q-ma-sm">
                    <q-form @submit="onSubmit" class="q-gutter-md">
                        <q-input
                            v-model="form.email"
                            :label="__('Email *')"
                            :hint="__('Login email')"
                            autofocus
                            autocomplete="email"
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
                        <div class="q-mt-lg row items-center justify-end">
                            <q-btn
                                v-if="canResetPassword"
                                @click="router.get(route('password.request'))"
                                flat
                                class="rounded text-sm"
                                text-color="grey-6"
                                :label="__('Forgot password?')"
                                style="text-decoration: underline"
                            />

                            <q-btn :label="__('Login')" color="primary" type="submit" class="full-width q-mt-md" />
                        </div>
                    </q-form>
                </q-card-section>
            </q-card>
        </q-page>
    </GuestLayout>
</template>
