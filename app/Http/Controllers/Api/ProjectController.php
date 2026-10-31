<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Projects\CreateProjectAction;
use App\Actions\Projects\DeleteProjectAction;
use App\Actions\Projects\UpdateProjectAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\StoreProjectRequest;
use App\Http\Requests\Projects\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    /**
     * Display a listing of the user's projects.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        /** @var User $user */
        $user = $request->user();

        $query = $user->projects()
            ->with('client')
            ->withCount('tasks')
            ->withSum(['timeEntries as total_logged_seconds' => fn ($q) => $q->whereNotNull('ended_at')], 'duration_seconds');

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->integer('client_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->value());
        }

        $projects = $query->orderBy('name')->get();

        return ProjectResource::collection($projects);
    }

    /**
     * Store a newly created project.
     */
    public function store(StoreProjectRequest $request, CreateProjectAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $project = $action->execute($user, $request->toData());

        return (new ProjectResource($project->load('client')->loadCount('tasks')))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project): ProjectResource
    {
        Gate::authorize('view', $project);

        return new ProjectResource($project->load('client')->loadCount('tasks'));
    }

    /**
     * Update the specified project.
     */
    public function update(
        UpdateProjectRequest $request,
        Project $project,
        UpdateProjectAction $action
    ): ProjectResource {
        Gate::authorize('update', $project);

        $updated = $action->execute($project, $request->toData());

        return new ProjectResource($updated->load('client')->loadCount('tasks'));
    }

    /**
     * Remove the specified project.
     */
    public function destroy(Project $project, DeleteProjectAction $action): JsonResponse
    {
        Gate::authorize('delete', $project);

        $projectName = $project->name;
        $action->execute($project);

        return response()->json([
            'message' => "Le projet « {$projectName} » a été supprimé avec succès.",
        ]);
    }
}
