<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\TimeEntries\LogManualTimeAction;
use App\Actions\TimeEntries\StartTimerAction;
use App\Actions\TimeEntries\StopTimerAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\TimeEntries\LogManualTimeRequest;
use App\Http\Requests\TimeEntries\StartTimerRequest;
use App\Http\Resources\TimeEntryResource;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class TimeEntryController extends Controller
{
    /**
     * Get the currently active running timer, if any.
     */
    public function active(Request $request): JsonResponse|TimeEntryResource
    {
        /** @var User $user */
        $user = $request->user();

        $activeTimer = $user->activeTimeEntry;

        if ($activeTimer === null) {
            return response()->json(['data' => null]);
        }

        return new TimeEntryResource($activeTimer->load(['project', 'task']));
    }

    /**
     * Start a new live timer.
     */
    public function start(StartTimerRequest $request, StartTimerAction $action): TimeEntryResource
    {
        /** @var User $user */
        $user = $request->user();

        $timeEntry = $action->execute($user, $request->toDTO());

        return new TimeEntryResource($timeEntry);
    }

    /**
     * Stop a running timer.
     */
    public function stop(Request $request, TimeEntry $timeEntry, StopTimerAction $action): TimeEntryResource
    {
        Gate::authorize('update', $timeEntry);

        $stoppedEntry = $action->execute($timeEntry);

        return new TimeEntryResource($stoppedEntry->load(['project', 'task']));
    }

    /**
     * List recent time entries for the user.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        /** @var User $user */
        $user = $request->user();

        $entries = $user->timeEntries()
            ->with(['project', 'task'])
            ->latest('started_at')
            ->paginate(30);

        return TimeEntryResource::collection($entries);
    }

    /**
     * Log a retroactive manual time entry.
     */
    public function store(LogManualTimeRequest $request, LogManualTimeAction $action): TimeEntryResource
    {
        /** @var User $user */
        $user = $request->user();

        $timeEntry = $action->execute($user, $request->toDTO());

        return new TimeEntryResource($timeEntry);
    }

    /**
     * Delete a time entry.
     */
    public function destroy(Request $request, TimeEntry $timeEntry): JsonResponse
    {
        Gate::authorize('delete', $timeEntry);

        $timeEntry->delete();

        return response()->json(['message' => 'Time entry deleted successfully.']);
    }
}
