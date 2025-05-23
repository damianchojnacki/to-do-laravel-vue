import { TaskStatus } from '@/types/TaskStatus';

export type UpdateTaskRequest = {
    title?: string;
    description?: string | null;
    status?: TaskStatus;
};
