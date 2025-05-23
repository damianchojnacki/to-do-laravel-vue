<script setup lang="ts" generic="T">
import type { Column } from '@tanstack/vue-table';
import { ListOrderedIcon, SortAscIcon, SortDescIcon } from 'lucide-vue-next';

import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';

interface DataTableColumnHeaderProps {
    column: Column<T>;
    title: string;
}

defineProps<DataTableColumnHeaderProps>();
</script>

<script lang="ts">
export default {
    inheritAttrs: false,
};
</script>

<template>
    <div v-if="column.getCanSort()" :class="cn('flex items-center space-x-2', $attrs.class ?? '')">
        <Button variant="ghost" size="sm" class="-ml-3 h-8 data-[state=open]:bg-accent" @click="column.toggleSorting(column.getIsSorted() === 'asc')">
            <span>{{ title }}</span>
            <SortDescIcon v-if="column.getIsSorted() === 'desc'" class="ml-2 h-4 w-4" />
            <SortAscIcon v-else-if="column.getIsSorted() === 'asc'" class="ml-2 h-4 w-4" />
            <ListOrderedIcon v-else class="ml-2 h-4 w-4" />
        </Button>
    </div>

    <div v-else :class="$attrs.class">
        {{ title }}
    </div>
</template>
