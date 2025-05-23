import { CreateTaskRequest } from '@/types/CreateTaskRequest';
import { PaginagedResponse } from '@/types/Response';
import { Task } from '@/types/Task';
import { UpdateTaskRequest } from '@/types/UpdateTaskRequest';
import axios from 'axios';
import { route, RouteParams } from 'ziggy-js';

const http = axios.create({
    headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
    },
});

export const listTasks = async (query: RouteParams<'tasks.index'>): Promise<PaginagedResponse<Task>> => {
    const response = await http.get<PaginagedResponse<Task>>(route('tasks.index', query));

    return response.data;
};

export const createTask = async (payload: CreateTaskRequest): Promise<Task> => {
    const response = await http.post<{ data: Task }>(route('tasks.store'), payload);

    return response.data.data;
};

export const updateTask = async (id: number, payload: UpdateTaskRequest): Promise<Task> => {
    const response = await http.put<{ data: Task }>(route('tasks.update', { id }), payload);

    return response.data.data;
};

export const deleteTask = async (id: number): Promise<void> => {
    await http.delete(route('tasks.destroy', { id }));

    return;
};
