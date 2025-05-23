import { createTask, deleteTask, updateTask } from '@/lib/api';
import { CreateTaskRequest } from '@/types/CreateTaskRequest';
import { Task } from '@/types/Task';
import { UpdateTaskRequest } from '@/types/UpdateTaskRequest';
import { defineStore } from 'pinia';

type State = {
    task: Task | null;
    isViewing: boolean;
    isEditing: boolean;
};

export const useTaskStore = defineStore('task', {
    state: (): State => ({ task: null, isViewing: false, isEditing: false }),
    actions: {
        view(task: Task) {
            this.task = task;
            this.isViewing = true;
            this.isEditing = false;
        },
        edit(task: Task) {
            this.task = task;
            this.isViewing = true;
            this.isEditing = true;
        },
        open() {
            this.isViewing = true;
            this.isEditing = true;
        },
        close() {
            this.isViewing = false;
            this.task = null;
        },
        async delete(task: Task) {
            await deleteTask(task.id);

            this.isEditing = false;
            this.isViewing = false;
            this.task = null;
        },
        async update(payload: UpdateTaskRequest) {
            if (!this.task?.id) {
                return;
            }

            this.task = await updateTask(this.task?.id, payload);
            this.isEditing = false;
        },
        async create(payload: CreateTaskRequest) {
            this.task = await createTask(payload);
            this.isEditing = false;
        },
    },
});
