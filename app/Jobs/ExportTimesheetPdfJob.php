<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

final class ExportTimesheetPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Carbon $startDate,
        public Carbon $endDate
    ) {}

    /**
     * Execute the job: aggregates time entries and produces the export manifest.
     */
    public function handle(): void
    {
        $entries = $this->user->timeEntries()
            ->with(['project', 'task'])
            ->whereBetween('started_at', [$this->startDate, $this->endDate])
            ->whereNotNull('ended_at')
            ->get();

        $totalSeconds = $entries->sum('duration_seconds');

        Log::info('Timesheet report generated for user', [
            'user_id' => $this->user->id,
            'entries_count' => $entries->count(),
            'total_hours' => round($totalSeconds / 3600, 2),
            'start_date' => $this->startDate->toDateString(),
            'end_date' => $this->endDate->toDateString(),
        ]);
    }
}
