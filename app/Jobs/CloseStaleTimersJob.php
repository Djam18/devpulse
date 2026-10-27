<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Events\TimerStopped;
use App\Models\TimeEntry;
use App\Notifications\LongRunningTimerNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

final class CloseStaleTimersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $maxHours = 12
    ) {}

    /**
     * Execute the job to automatically cap or close timers running longer than maxHours.
     */
    public function handle(): void
    {
        $threshold = Carbon::now()->subHours($this->maxHours);

        $staleEntries = TimeEntry::query()
            ->whereNull('ended_at')
            ->where('started_at', '<=', $threshold)
            ->get();

        foreach ($staleEntries as $entry) {
            $capTime = $entry->started_at->copy()->addHours($this->maxHours);
            $entry->update([
                'ended_at' => $capTime,
                'duration_seconds' => $this->maxHours * 3600,
            ]);

            event(new TimerStopped($entry));

            $entry->user->notify(new LongRunningTimerNotification($entry));

            Log::warning('Stale active timer capped automatically by CloseStaleTimersJob', [
                'time_entry_id' => $entry->id,
                'user_id' => $entry->user_id,
                'capped_at_hours' => $this->maxHours,
            ]);
        }
    }
}
