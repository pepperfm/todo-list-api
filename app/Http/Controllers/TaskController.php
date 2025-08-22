<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;

class TaskController
{
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        // по заданию отдаю все задачи
        $tasks = Task::all();

        return TaskResource::collection($tasks);
    }

    public function store(TaskStoreRequest $request): \Illuminate\Http\Resources\Json\JsonResource
    {
        $task = Task::create($request->validated());

        return TaskResource::make($task);
    }

    public function show(Task $task): \Illuminate\Http\Resources\Json\JsonResource
    {
        return TaskResource::make($task);
    }

    public function update(TaskUpdateRequest $request, Task $task): TaskResource
    {
        $task->update($request->validated());

        return TaskResource::make($task);
    }

    public function destroy(Task $task): \Illuminate\Http\JsonResponse
    {
        $task->delete();

        return response()->json(status: 204);
    }
}
