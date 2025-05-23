import type { TaskStatus } from '@/types/TaskStatus';
import { CheckCircle, Clock } from 'lucide-vue-next';
import { h } from 'vue';

export const statuses: { value: TaskStatus; label: string; icon: any }[] = [
    {
        value: 'PENDING',
        label: 'Pending',
        icon: h(Clock),
    },
    {
        value: 'COMPLETED',
        label: 'Completed',
        icon: h(CheckCircle),
    },
];
