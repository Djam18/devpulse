<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Tasks\CreateTaskAction;
use App\Actions\Tasks\DeleteTaskAction;
use App\Actions\Tasks\UpdateTaskAction;
use App\Actions\Tasks\UpdateTaskStatusAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\StoreTaskRequest;
use App\Http\Requests\Tasks\UpdateTaskRequest;
use App\Http\Requests\Tasks\UpdateTaskStatusRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    /**
     * Display a listing of the user's tasks.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        /** @var User $user */
        $user = $request->user();

        $query = $user->tasks()->with('project');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->integer('project_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->value());
        }

        $tasks = $query->orderBy('created_at', 'desc')->get();

        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created task.
     */
    public function store(StoreTaskRequest $request, CreateTaskAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $task = $action->execute($user, $request->toData());

        return (new TaskResource($task->load('project')))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task): TaskResource
    {
        Gate::authorize('view', $task);

        return new TaskResource($task->load('project'));
    }

    /**
     * Update the specified task.
     */
    public function update(
        UpdateTaskRequest $request,
        Task $task,
        UpdateTaskAction $action
    ): TaskResource {
        Gate::authorize('update', $task);

        $updatedTask = $action->execute($task, $request->toData());

        return new TaskResource($updatedTask);
    }

    /**
     * Update the status of the specified task (Kanban drag-and-drop or column shift).
     */
    public function updateStatus(
        UpdateTaskStatusRequest $request,
        Task $task,
        UpdateTaskStatusAction $action
    ): TaskResource {
        Gate::authorize('update', $task);

        $updatedTask = $action->execute($task, $request->toData());

        return new TaskResource($updatedTask);
    }

    /**
     * Remove the specified task.
     */
    public function destroy(Task $task, DeleteTaskAction $action): JsonResponse
    {
        Gate::authorize('delete', $task);

        $action->execute($task);

        return response()->json(null, 204);
    }
}
