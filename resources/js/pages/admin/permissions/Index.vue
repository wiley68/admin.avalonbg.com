<script lang="ts" setup>
import { __ } from '@/composables/useTranslate';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { useQuasar } from 'quasar';

defineProps<{
    permissions: array;
}>();

const columns = [
    {
        name: 'id',
        required: true,
        label: '№',
        align: 'left',
        field: 'id',
        style: 'width: 80px;',
        sortable: true,
    },
    {
        name: 'name',
        align: 'left',
        label: __('Name'),
        field: 'name',
        sortable: true,
    },
    {
        name: 'actions',
        label: __('Actions'),
        align: 'center',
        field: 'actions',
    },
];

const title = __('Permissions');
const $q = useQuasar();

const confirm = (permission_id) => {
    $q.dialog({
        title: __('Confirm'),
        message: __('Do you want to delete the permission?'),
        cancel: true,
        persistent: true,
        ok: {
            label: 'Yes',
            color: 'primary',
        },
        cancel: {
            label: 'Cancel',
            color: 'grey-1',
            textColor: 'grey-10',
            flat: true,
        },
    })
        .onOk(() => {
            router.delete(route('permissions.destroy', permission_id), {
                onError: (errors) => {
                    Object.values(errors)
                        .flat()
                        .forEach((error) => {
                            $q.notify({
                                message: error,
                                icon: 'mdi-alert-circle-outline',
                                type: 'negative',
                            });
                        });
                },
            });
        })
        .onCancel(() => {})
        .onDismiss(() => {});
};
</script>

<template>
    <Head :title="title"></Head>

    <DefaultLayout :title="title" icon="mdi-file-document-outline">
        <q-page class="q-pa-none">
            <div class="page-container">
                <div class="body-panel">
                    <div class="scrollable-content">
                        <q-table
                            bordered
                            :title="__('Permissions')"
                            :rows-per-page-label="__('Rows per page')"
                            separator="cell"
                            :no-data-label="__('No data available')"
                            :no-results-label="__('No results found')"
                            :loading-label="__('Loading')"
                            :rows="permissions"
                            :columns="columns"
                            row-key="id"
                        >
                            <template v-slot:body-cell-actions="props">
                                <q-td align="center" style="width: 80px">
                                    <q-btn
                                        icon="mdi-pencil-outline"
                                        color="primary"
                                        :title="__('Edit permission')"
                                        dense
                                        flat
                                        rounded
                                        @click="router.get(route('permissions.edit', props.row.id))"
                                    />
                                    <q-btn
                                        icon="mdi-delete-outline"
                                        color="negative"
                                        :title="__('Delete permission')"
                                        dense
                                        flat
                                        rounded
                                        @click="confirm(props.row.id)"
                                    />
                                </q-td>
                            </template>
                        </q-table>
                    </div>
                </div>
                <div class="footer-panel">
                    <q-btn color="primary" label="Табло" flat icon="mdi-menu-left" @click="router.get(route('admin.index'))" />

                    <q-btn color="primary" label="Ново право" icon="mdi-key-plus" @click="router.get(route('permissions.create'))" />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
