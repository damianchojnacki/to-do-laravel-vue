import DataTable from '@/components/DataTable/DataTable.vue';
import DataTableColumnHeader from '@/components/DataTable/DataTableColumnHeader.vue';
import { ColumnDef } from '@tanstack/vue-table';
import { mount } from '@vue/test-utils';
import { createPinia, setActivePinia } from 'pinia';
import { beforeEach, expect, test } from 'vitest';
import { h } from 'vue';

beforeEach(() => {
    setActivePinia(createPinia());
});

test('renders an empty data table', () => {
    const wrapper = mount(DataTable, {
        props: {
            data: {
                data: [],
                meta: {
                    current_page: 1,
                    from: 0,
                    last_page: 1,
                    per_page: 10,
                    to: 0,
                    total: 0,
                },
            },
            columns: [],
        },
    });

    expect(wrapper.text()).toContain('No results.');
});

test('renders an data table with supplied data', () => {
    const data = {
        data: [
            {
                id: 999,
                name: 'Example',
            },
        ],
        meta: {
            current_page: 1,
            from: 1,
            last_page: 1,
            per_page: 10,
            to: 1,
            total: 1,
        },
    };

    const columns: ColumnDef<(typeof data.data)[0]>[] = [
        {
            accessorKey: 'name',
            header: ({ column }) => h(DataTableColumnHeader, { column, title: 'Name' }),
            cell: ({ row }) => h('div', row.getValue('name')),
        },
    ];

    const wrapper = mount(DataTable, { props: { data, columns } });

    expect(wrapper.text()).not.toContain(999);
    expect(wrapper.text()).toContain('Example');
});
