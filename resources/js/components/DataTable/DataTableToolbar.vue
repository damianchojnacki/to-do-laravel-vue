<script setup lang="ts" generic="T">
import type { Table } from '@tanstack/vue-table';
import { computed } from 'vue';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { PlusIcon, XIcon } from 'lucide-vue-next';

import DataTableFacetedFilter from '@/components/DataTable/DataTableFacetedFilter.vue';
import { statuses } from '@/components/Task/statuses';
import { useTaskStore } from '@/lib/stores/useTaskStore';

interface DataTableToolbarProps {
    table: Table<T>;
}

const props = defineProps<DataTableToolbarProps>();

const isFiltered = computed(() => props.table.getState().columnFilters.length > 0);

const store = useTaskStore();
</script>

<template>
    <div class="flex items-center justify-between flex-col sm:flex-row gap-3">
        <div class="flex flex-1 items-center justify-between sm:justify-start gap-2 w-full sm:w-auto">
            <Input
                placeholder="Filter tasks..."
                :model-value="(table.getColumn('title')?.getFilterValue() as string) ?? ''"
                class="h-8 w-[150px] lg:w-[250px]"
                @input="table.getColumn('title')?.setFilterValue($event.target.value)"
            />

            <DataTableFacetedFilter v-if="table.getColumn('status')" :column="table.getColumn('status')" title="Status" :options="statuses" />

            <Button v-if="isFiltered" variant="ghost" class="h-8 px-2 lg:px-3" @click="table.resetColumnFilters()">
                Reset
                <XIcon class="ml-2 h-4 w-4" />
            </Button>
        </div>

        <Button variant="outline" class="h-8 px-2 lg:px-3 w-full sm:w-auto" @click="store.open()">
            <PlusIcon class="mr-2 h-4 w-4" />

            Create new
        </Button>
    </div>
</template>
