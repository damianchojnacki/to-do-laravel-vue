<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
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
    public function index(): AnonymousResourceCollection
    {
        $tasks = Task::query()
            ->orderBy('status', 'desc')
            ->paginate();

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
