<script lang="ts" setup>
import { __ } from '@/composables/useTranslate';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';
import { useQuasar } from 'quasar';
import { onMounted, ref, watch } from 'vue';

const $q = useQuasar();
const permissions = ref([]);
const loading = ref(false);
const page = ref(1);
const perPage = 20;
const total = ref(0);
const sortBy = ref('id');
const sortDesc = ref(false);
const search = ref('');
const finished = ref(false);

const columns = [
    { name: 'id', required: true, label: '№', textAlign: 'left', field: 'id', width: '80px', sortable: true },
    { name: 'name', textAlign: 'left', label: __('Name'), field: 'name', width: '100%', sortable: true },
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
        onSuccess: (page) => {
            const newData = page.props.permissions.data;
            total.value = page.props.permissions.total;

            if (reset) {
                permissions.value = newData;
            } else {
                permissions.value = [...permissions.value, ...newData];
            }

            if (permissions.value.length >= total.value) {
                finished.value = true;
            }

            loading.value = false;
        },
    });
}

const onScroll = ({ to }: { to: number }) => {
    if (to === permissions.value.length - 1 && !finished.value) {
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

const confirm = (permission_id: number) => {
    $q.dialog({
        title: __('Confirm'),
        message: __('Do you want to delete the permission?'),
        persistent: true,
        ok: { label: 'Yes', color: 'primary' },
        cancel: { label: __('Cancel'), color: 'grey-1', textColor: 'grey-10', flat: true },
    }).onOk(() => {
        router.delete(route('permissions.destroy', permission_id), {
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
                    <div class="rounded-borders shadow-4">
                        <div class="q-pa-sm row items-center justify-between">
                            <div class="col-auto">
                                <div class="text-h5">{{ title }}</div>
                                <div class="text-caption">
                                    {{ __('Sorted by') }}: <strong>{{ sortBy }}</strong> ({{ sortDesc ? 'desc' : 'asc' }}) | {{ __('Total') }}:
                                    <strong>{{ total }}</strong>
                                </div>
                            </div>
                            <div class="col-auto" style="max-width: 50%">
                                <q-input v-model="search" :label="__('Search ...')" debounce="500" clearable>
                                    <template #append>
                                        <q-icon name="search" />
                                    </template>
                                </q-input>
                            </div>
                        </div>

                        <div class="q-pa-sm">
                            <q-markup-table flat class="q-mb-none">
                                <thead>
                                    <tr>
                                        <th
                                            v-for="col in columns"
                                            :key="col.field"
                                            :style="`width: ${col.width}; text-align: ${col.textAlign}; cursor: ${col.sortable ? 'pointer' : 'default'};`"
                                            @click="col.sortable && onSort(col.field)"
                                        >
                                            {{ col.label }}
                                            <q-icon :name="sortBy === col.field ? (sortDesc ? 'mdi-arrow-down' : 'mdi-arrow-up') : ''" size="xs" />
                                        </th>
                                    </tr>
                                </thead>
                            </q-markup-table>

                            <q-virtual-scroll
                                :items="permissions"
                                :virtual-scroll-item-size="48"
                                :items-per-page="perPage"
                                @virtual-scroll="onScroll"
                                class="scrollable-content"
                                style="height: calc(100vh - 310px)"
                            >
                                <template #default="{ item: row }">
                                    <div class="row q-pa-sm items-center">
                                        <div class="col-auto" style="width: 80px">{{ row.id }}</div>
                                        <div class="col text-left">{{ row.name }}</div>
                                        <div class="col-auto" style="width: 120px">
                                            <q-btn
                                                icon="mdi-pencil-outline"
                                                color="primary"
                                                dense
                                                flat
                                                rounded
                                                @click="router.get(route('permissions.edit', row.id))"
                                            />
                                            <q-btn icon="mdi-delete-outline" color="negative" dense flat rounded @click="confirm(row.id)" />
                                        </div>
                                    </div>
                                    <q-separator />
                                </template>
                            </q-virtual-scroll>
                        </div>
                    </div>
                </div>

                <div class="footer-panel q-pa-sm">
                    <q-btn color="primary" :label="__('Dashboard')" flat icon="mdi-menu-left" @click="router.get(route('admin.index'))" />
                    <q-btn color="primary" :label="__('New permission')" icon="mdi-key-plus" @click="router.get(route('permissions.create'))" />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
