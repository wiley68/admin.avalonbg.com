<script lang="ts" setup>
import { router } from '@inertiajs/vue3';

defineProps<{
    title: string;
    sortBy: string;
    sortDesc: boolean;
    total: number;
    columns: Array<{
        name: string;
        label: string;
        textAlign?: string;
        field: string;
        width?: string;
        sortable?: boolean;
    }>;
    items: Array<Record<string, any>>;
    perPage: number;
}>();

const searchModelValue = defineModel<string>('search');

const emit = defineEmits<{
    (e: 'sort', field: string): void;
    (e: 'scroll', to: number): void;
    (e: 'confirm', field: string): void;
}>();
</script>

<template>
    <div class="rounded-borders shadow-4">
        <div class="q-pa-sm row items-center justify-between">
            <div class="col-auto">
                <div class="text-h5">{{ title }}</div>
                <div class="text-caption">
                    {{ __('Sorted by') }}: <strong>{{ sortBy }}</strong> ({{ sortDesc ? __('desc') : __('asc') }}) | {{ __('Total records') }}:
                    <strong>{{ total }}</strong>
                </div>
            </div>
            <div class="col-auto" style="max-width: 50%">
                <q-input v-model="searchModelValue" :label="__('Search ...')" debounce="500" clearable>
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
                            @click="col.sortable && emit('sort', col.field)"
                        >
                            {{ col.label }}
                            <q-icon :name="sortBy === col.field ? (sortDesc ? 'mdi-arrow-down' : 'mdi-arrow-up') : ''" size="xs" />
                        </th>
                    </tr>
                </thead>
            </q-markup-table>

            <q-virtual-scroll
                :items="items"
                :virtual-scroll-item-size="48"
                :items-per-page="perPage"
                @virtual-scroll="({ to }) => emit('scroll', to)"
                class="scrollable-content"
                style="height: calc(100vh - 310px)"
            >
                <template #default="{ item: row }">
                    <q-markup-table flat class="q-mb-none">
                        <thead>
                            <tr>
                                <th v-for="col in columns" :key="col.field" :style="`width: ${col.width}; text-align: ${col.textAlign};`">
                                    <template v-if="col.field === 'actions'">
                                        <q-btn
                                            icon="mdi-pencil-outline"
                                            color="primary"
                                            title="Промяна на правото"
                                            dense
                                            flat
                                            rounded
                                            @click="router.get(route('permissions.edit', row.id))"
                                        />
                                        <q-btn
                                            icon="mdi-delete-outline"
                                            color="negative"
                                            title="Изтриване на правото"
                                            dense
                                            flat
                                            rounded
                                            @click="emit('confirm', row.id)"
                                        />
                                    </template>
                                    <template v-else>
                                        {{ row[col.field] }}
                                    </template>
                                </th>
                            </tr>
                        </thead>
                    </q-markup-table>
                </template>
            </q-virtual-scroll>
        </div>
    </div>
</template>
