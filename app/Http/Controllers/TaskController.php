<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\ListTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;

class TaskController extends Controller
{
    /**
     * List tasks
     */
    public function index(ListTaskRequest $request): AnonymousResourceCollection
    {
        $query = Task::query();

        foreach ($request->array('sorting') as $sortable) {
            $query->orderBy($sortable['id'], $sortable['desc'] ? 'desc' : 'asc');
        }

        foreach ($request->array('filters') as $filter) {
            $query->where($filter['id'], 'like', '%' . $filter['value'] . '%');
        }

        $tasks = $query->paginate(12);

        return TaskResource::collection($tasks);
    }

    /**
     * Create a task
     */
    public function store(CreateTaskRequest $request): TaskResource
    {
        $task = Task::create($request->validated());

        return new TaskResource($task->fresh());
    }

    /**
     * Update a task
     */
    public function update(UpdateTaskRequest $request, Task $task): TaskResource
    {
        $task->update($request->validated());

        return new TaskResource($task);
    }

    /**
     * Delete a task
     *
     * @throws Throwable
     */
    public function destroy(Task $task): JsonResponse
    {
        $task->deleteOrFail();

        return response()->json(status: 204);
    }
}
