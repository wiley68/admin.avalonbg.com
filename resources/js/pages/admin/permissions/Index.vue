<script lang="ts" setup>
import Table from '@/components/Table.vue';
import { __ } from '@/composables/useTranslate';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { useQuasar } from 'quasar';
import { onMounted, ref, watch } from 'vue';

const $q = useQuasar();
const permissions = ref<any[]>([]);
const loading = ref(false);
const page = ref(1);
const perPage = 40;
const total = ref(0);
const sortBy = ref('id');
const sortDesc = ref(false);
const search = ref('');
const finished = ref(false);

const columns = [
    { name: 'id', label: '№', textAlign: 'left', field: 'id', width: '80px', sortable: true },
    { name: 'name', label: __('Name'), textAlign: 'left', field: 'name', width: '100%', sortable: true },
    { name: 'actions', label: __('Actions'), textAlign: 'left', field: 'actions', width: '120px', sortable: false },
];

const title = __('Permissions');

function fetchPermissions(reset = false) {
    if (loading.value || finished.value) return;
    loading.value = true;

    router.visit(route('permissions.index'), {
        method: 'get',
        only: ['permissions'],
        preserveState: true,
        preserveScroll: true,
        data: {
            page: page.value,
            per_page: perPage,
            sort: sortBy.value,
            direction: sortDesc.value ? 'desc' : 'asc',
            search: search.value,
        },
        onSuccess: (response) => {
            const permissionsData = response.props.permissions as { data: any[]; total: number };
            if (permissionsData) {
                const newData = permissionsData.data;
                total.value = permissionsData.total;

                if (reset) {
                    permissions.value = newData;
                } else {
                    permissions.value = [...permissions.value, ...newData];
                }

                if (permissions.value.length >= total.value) {
                    finished.value = true;
                } else {
                    finished.value = false;
                }
            }
            loading.value = false;
        },
    });
}

const onScroll = (to: number) => {
    const buffer = 5;
    if (to >= permissions.value.length - buffer && !finished.value && !loading.value) {
        page.value++;
        fetchPermissions();
    }
};

const onSort = (columnName: string) => {
    if (sortBy.value === columnName) {
        sortDesc.value = !sortDesc.value;
    } else {
        sortBy.value = columnName;
        sortDesc.value = false;
    }
    page.value = 1;
    finished.value = false;
    fetchPermissions(true);
};

const debouncedSearch = debounce(() => {
    page.value = 1;
    finished.value = false;
    fetchPermissions(true);
}, 500);

watch(search, debouncedSearch);

onMounted(() => {
    fetchPermissions(true);
});

const confirm = (permission_id: string) => {
    $q.dialog({
        title: __('Confirm'),
        message: __('Do you want to delete the permission?'),
        persistent: true,
        ok: { label: __('Yes'), color: 'primary' },
        cancel: { label: __('Cancel'), color: 'grey-1', textColor: 'grey-10', flat: true },
    }).onOk(() => {
        router.delete(route('permissions.destroy', Number(permission_id)), {
            onSuccess: () => {
                page.value = 1;
                finished.value = false;
                fetchPermissions(true);
            },
            onError: (errors) => {
                Object.values(errors)
                    .flat()
                    .forEach((error) => {
                        $q.notify({ message: error, icon: 'mdi-alert-circle-outline', type: 'negative' });
                    });
            },
        });
    });
};
</script>

<template>
    <Head :title="title"></Head>

    <DefaultLayout :title="title" icon="mdi-shield-key">
        <q-page class="q-pa-none">
            <div class="page-container">
                <div class="body-panel q-pa-md">
                    <Table
                        v-model:search="search"
                        :title="title"
                        :sortBy="sortBy"
                        :sortDesc="sortDesc"
                        :total="total"
                        :columns="columns"
                        :items="permissions"
                        :perPage="perPage"
                        @sort="onSort"
                        @scroll="onScroll"
                        @confirm="confirm"
                    ></Table>
                </div>

                <div class="footer-panel q-pa-sm">
                    <q-btn color="primary" :label="__('Dashboard')" flat icon="mdi-menu-left" @click="router.get(route('admin.index'))" />
                    <q-btn color="primary" :label="__('New permission')" icon="mdi-key-plus" @click="router.get(route('permissions.create'))" />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
