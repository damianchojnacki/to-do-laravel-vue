<script setup lang="ts" generic="T">
import { Button } from '@/components/ui/button';
import type { Table } from '@tanstack/vue-table';
import { ChevronLeftIcon, ChevronRightIcon, ChevronsLeftIcon, ChevronsRightIcon } from 'lucide-vue-next';

interface DataTablePaginationProps {
    table: Table<T>;
}

defineProps<DataTablePaginationProps>();
</script>

<template>
    <div class="flex items-center justify-end px-2">
        <div class="flex items-center space-x-6 lg:space-x-8">
            <div class="flex w-[100px] items-center justify-center text-sm font-medium">
                Page {{ table.getState().pagination.pageIndex + 1 }} of
                {{ table.getPageCount() }}
            </div>
            <div class="flex items-center space-x-2">
                <Button variant="outline" class="hidden h-8 w-8 p-0 lg:flex" :disabled="!table.getCanPreviousPage()" @click="table.setPageIndex(0)">
                    <span class="sr-only">Go to first page</span>
                    <ChevronsLeftIcon class="h-4 w-4" />
                </Button>
                <Button variant="outline" class="h-8 w-8 p-0" :disabled="!table.getCanPreviousPage()" @click="table.previousPage()">
                    <span class="sr-only">Go to previous page</span>
                    <ChevronLeftIcon class="h-4 w-4" />
                </Button>
                <Button variant="outline" class="h-8 w-8 p-0" :disabled="!table.getCanNextPage()" @click="table.nextPage()">
                    <span class="sr-only">Go to next page</span>
                    <ChevronRightIcon class="h-4 w-4" />
                </Button>
                <Button
                    variant="outline"
                    class="hidden h-8 w-8 p-0 lg:flex"
                    :disabled="!table.getCanNextPage()"
                    @click="table.setPageIndex(table.getPageCount() - 1)"
                >
                    <span class="sr-only">Go to last page</span>
                    <ChevronsRightIcon class="h-4 w-4" />
                </Button>
            </div>
        </div>
    </div>
</template>
