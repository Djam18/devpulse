<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TaskPageController extends Controller
{
    /**
     * Display the Kanban tasks board.
     */
    public function index(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();

        $projects = $user->projects()
            ->select(['id', 'name', 'color'])
            ->orderBy('name')
            ->get();

        return Inertia::render('Tasks/Index', [
            'projects' => $projects,
        ]);
    }
}
