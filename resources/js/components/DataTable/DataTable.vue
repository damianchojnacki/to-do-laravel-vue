<script setup lang="ts" generic="T extends DataRecord">
import type { ColumnDef, ColumnFiltersState, SortingState } from '@tanstack/vue-table';

import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { valueUpdater } from '@/lib/utils';
import { FlexRender, getCoreRowModel, getFacetedRowModel, getFacetedUniqueValues, useVueTable } from '@tanstack/vue-table';
import { ref, watch } from 'vue';

import DataTableToolbar from '@/components/DataTable/DataTableToolbar.vue';
import { PaginatedResponse } from '@/types/Response';
import DataTablePagination from './DataTablePagination.vue';
import {DataRecord} from "@/types/DataRecord"

type DataTableQuery = { sorting: SortingState; filters: ColumnFiltersState; page: number };

type DataTableProps = {
    columns: ColumnDef<T, any>[];
    data: PaginatedResponse<T>;
    query?: DataTableQuery;
};

const props = defineProps<DataTableProps>();

const emit = defineEmits<{
    'update:query': [DataTableQuery];
}>();

const sorting = ref<SortingState>(props.query?.sorting ?? []);
const columnFilters = ref<ColumnFiltersState>(props.query?.filters ?? []);
const pagination = ref({
    pageIndex: (props.query?.page ?? 1) - 1,
    pageSize: props.data.meta.per_page,
});

const animate = ref(true)
watch(props.data, () => animate.value = true)

watch([sorting, columnFilters, pagination], () => {
    emit('update:query', {
        sorting: sorting.value,
        filters: columnFilters.value,
        page: pagination.value.pageIndex + 1,
    });
});

const table = useVueTable({
    get data() {
        return props.data.data;
    },
    get columns() {
        return props.columns;
    },
    state: {
        get sorting() {
            return sorting.value;
        },
        get columnFilters() {
            return columnFilters.value;
        },
        get pagination() {
            return pagination.value;
        },
    },
    enableRowSelection: false,
    manualSorting: true,
    manualPagination: true,
    manualFiltering: true,
    rowCount: props.data.meta.total,
    onSortingChange: (updaterOrValue) => valueUpdater(updaterOrValue, sorting),
    onColumnFiltersChange: (updaterOrValue) => valueUpdater(updaterOrValue, columnFilters),
    onPaginationChange: (updaterOrValue) => {
        animate.value = false

        valueUpdater(updaterOrValue, pagination)
    },
    getCoreRowModel: getCoreRowModel(),
    getFacetedRowModel: getFacetedRowModel(),
    getFacetedUniqueValues: getFacetedUniqueValues(),
});
</script>

<template>
    <div class="space-y-4">
        <DataTableToolbar :table="table" />

        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                        <TableHead v-for="header in headerGroup.headers" :key="header.id">
                            <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header" :props="header.getContext()" />
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <template v-if="table.getRowModel().rows?.length">
                        <TransitionGroup
                            name="table"
                            :css="animate"
                        >
                            <TableRow v-for="row in table.getRowModel().rows" :key="row.original.id">
                                <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                                    <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                                </TableCell>
                            </TableRow>
                        </TransitionGroup>
                    </template>

                    <TableRow v-else>
                        <TableCell :colspan="columns.length" class="h-24 text-center">No results.</TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <DataTablePagination :table="table" />
    </div>
</template>
