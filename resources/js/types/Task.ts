import { TaskStatus } from '@/types/TaskStatus';

export type Task = {
    id: number;
    title: string;
    description: string | null;
    status: TaskStatus;
    created_at: string | null;
};
