import { TaskStatus } from '@/types/TaskStatus';

export type CreateTaskRequest = {
    title: string;
    description?: string | null;
    status?: TaskStatus | null;
};
