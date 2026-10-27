<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Dashboard\GetDashboardMetricsAction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class DashboardController extends Controller
{
    /**
     * Display the developer productivity dashboard.
     */
    public function index(Request $request, GetDashboardMetricsAction $action): Response
    {
        return Inertia::render('Dashboard', $action->execute($request->user()));
    }
}
