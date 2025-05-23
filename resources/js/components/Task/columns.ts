import DataTableColumnHeader from '@/components/DataTable/DataTableColumnHeader.vue';
import DataTableRowActions from '@/components/DataTable/DataTableRowActions.vue';
import { statuses } from '@/components/Task/statuses';
import { Task } from '@/types/Task';
import { ColumnDef } from '@tanstack/vue-table';
import { h } from 'vue';

export const columns: ColumnDef<Task>[] = [
    {
        accessorKey: 'title',
        header: ({ column }) => h(DataTableColumnHeader, { column, title: 'Title' }),
        cell: ({ row }) => h('div', { class: 'max-w-[150px] md:max-w-[500px] truncate font-medium' }, row.getValue('title')),
    },
    {
        accessorKey: 'status',
        header: ({ column }) => h(DataTableColumnHeader, { column, title: 'Status', class: 'w-0' }),
        cell: ({ row }) => {
            const status = statuses.find((status) => status.value === row.getValue('status'));

            if (!status) return null;

            return h('div', { class: 'flex w-[30px] md:w-[100px] items-center' }, [
                h(status.icon, { class: 'mr-2 h-4 w-4 text-muted-foreground' }),
                h('span', { class: 'hidden md:block' }, status.label),
            ]);
        },
        filterFn: (row, id, value) => {
            return value.includes(row.getValue(id));
        },
    },
    {
        id: 'actions',
        cell: ({ row }) => h(DataTableRowActions, { row }),
    },
];
