<template>
    <div class="mx-auto lg:container">
        <div class="flex h-full flex-1 flex-col space-y-8 p-8">
            <div class="flex items-center justify-between space-y-2">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Welcome back!</h2>
                    <p class="text-muted-foreground">
                        Here's a list of your tasks! You can filter them, sort, create new, edit existing one or delete some.
                    </p>
                </div>
            </div>

            <DataTable
                v-if="data"
                :data="data"
                :columns="columns"
                :query="queryArgs"
                @update:query="({ sorting, filters, page }) => (queryArgs = { sorting, filters, page })"
            />

            <Modal />
        </div>
    </div>
</template>

<script setup lang="ts">
import DataTable from '@/components/DataTable/DataTable.vue';
import { columns } from '@/components/Task/columns';
import Modal from '@/components/Task/View.vue';
import { useToast } from '@/components/ui/toast';
import { listTasks } from '@/lib/api';
import { useQuery } from '@tanstack/vue-query';
import type { ColumnFiltersState, SortingState } from '@tanstack/vue-table';
import { isAxiosError } from 'axios';
import { ref, watch } from 'vue';

const queryArgs = ref<{ sorting: SortingState; filters: ColumnFiltersState; page: number }>({
    sorting: [
        {
            id: 'status',
            desc: true,
        },
        {
            id: 'created_at',
            desc: true,
        },
    ],
    filters: [],
    page: 1,
});

const { data, error } = useQuery({
    queryKey: ['tasks', queryArgs],
    queryFn: () => listTasks(queryArgs.value),
    placeholderData: (previousData) => previousData,
});

const { toast } = useToast();

watch(error, (e) => {
    let message = e?.message ?? 'An unknown error occurred';

    if (isAxiosError(e)) {
        message = e.response?.data?.message ?? e.message;
    }

    toast({
        title: 'Oops... Something went wrong!',
        description: message,
        variant: 'destructive',
    });
});
</script>
