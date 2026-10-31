<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Http\Resources\ProjectResource;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectPageController extends Controller
{
    /**
     * Render the Projects & Clients management page.
     */
    public function index(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();

        $projects = $user->projects()
            ->with('client')
            ->withCount('tasks')
            ->withSum(['timeEntries as total_logged_seconds' => fn ($q) => $q->whereNotNull('ended_at')], 'duration_seconds')
            ->orderBy('name')
            ->get();

        $clients = $user->clients()
            ->withCount('projects')
            ->orderBy('name')
            ->get();

        return Inertia::render('Projects/Index', [
            'initialProjects' => ProjectResource::collection($projects)->resolve(),
            'initialClients' => ClientResource::collection($clients)->resolve(),
        ]);
    }
}
