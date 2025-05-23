<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuShortcut, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import type { Row } from '@tanstack/vue-table';
import { EllipsisVerticalIcon, PencilIcon, SquareArrowOutUpRightIcon, TrashIcon } from 'lucide-vue-next';
import { computed } from 'vue';

import { useTaskStore } from '@/lib/stores/useTaskStore';
import { Task } from '@/types/Task';
import { useQueryClient } from '@tanstack/vue-query';

interface DataTableRowActionsProps {
    row: Row<Task>;
}

const props = defineProps<DataTableRowActionsProps>();

const task = computed(() => props.row.original);

const store = useTaskStore();
const queryClient = useQueryClient();

const handleDelete = async () => {
    await store.delete(task.value);

    await queryClient.invalidateQueries({ queryKey: ['tasks'] });
};
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="ghost" class="flex h-8 w-8 p-0 data-[state=open]:bg-muted">
                <EllipsisVerticalIcon class="h-4 w-4" />
                <span class="sr-only">Open menu</span>
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end" class="w-[160px]">
            <DropdownMenuItem class="cursor-pointer" @click="store.view(task)">
                View
                <DropdownMenuShortcut>
                    <SquareArrowOutUpRightIcon class="h-4 w-4" />
                </DropdownMenuShortcut>
            </DropdownMenuItem>
            <DropdownMenuItem class="cursor-pointer" @click="store.edit(task)">
                Edit
                <DropdownMenuShortcut>
                    <PencilIcon class="h-4 w-4" />
                </DropdownMenuShortcut>
            </DropdownMenuItem>
            <DropdownMenuItem class="cursor-pointer" @click="handleDelete">
                Delete
                <DropdownMenuShortcut>
                    <TrashIcon class="h-4 w-4" />
                </DropdownMenuShortcut>
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
