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
const perPage = 100;
const total = ref(0);
const sortBy = ref('id');
const sortDesc = ref(false);
const search = ref('');
const finished = ref(false);

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

const onSort = (col: any) => {
    sortBy.value = col.name;
    sortDesc.value = col.order === 'desc';
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
                <div class="body-panel">
                    <div class="q-pa-sm">
                        <q-input v-model="search" :label="__('Search permissions')" debounce="500" outlined clearable>
                            <template #append>
                                <q-icon name="search" />
                            </template>
                        </q-input>
                    </div>

                    <q-virtual-scroll
                        :items="permissions"
                        :virtual-scroll-item-size="48"
                        :items-per-page="perPage"
                        @virtual-scroll="onScroll"
                        class="scrollable-content q-pa-sm"
                        style="height: calc(100vh - 220px)"
                    >
                        <template #default="{ item: row, index }">
                            <q-item>
                                <q-item-section>{{ row.id }}</q-item-section>
                                <q-item-section>{{ row.name }}</q-item-section>
                                <q-item-section side>
                                    <q-btn
                                        icon="mdi-pencil-outline"
                                        color="primary"
                                        :title="__('Edit permission')"
                                        dense
                                        flat
                                        rounded
                                        @click="router.get(route('permissions.edit', row.id))"
                                    />
                                    <q-btn
                                        icon="mdi-delete-outline"
                                        color="negative"
                                        :title="__('Delete permission')"
                                        dense
                                        flat
                                        rounded
                                        @click="confirm(row.id)"
                                    />
                                </q-item-section>
                            </q-item>
                            <q-separator />
                        </template>
                    </q-virtual-scroll>
                </div>

                <div class="footer-panel">
                    <q-btn color="primary" :label="__('Dashboard')" flat icon="mdi-menu-left" @click="router.get(route('admin.index'))" />
                    <q-btn color="primary" :label="__('New permission')" icon="mdi-key-plus" @click="router.get(route('permissions.create'))" />
                </div>
            </div>
        </q-page>
    </DefaultLayout>
</template>
