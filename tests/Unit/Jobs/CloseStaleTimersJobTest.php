<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use App\Jobs\CloseStaleTimersJob;
use App\Models\TimeEntry;
use App\Models\User;
use App\Notifications\LongRunningTimerNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class CloseStaleTimersJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_caps_stale_timers_and_notifies_user(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $staleEntry = TimeEntry::factory()->create([
            'user_id' => $user->id,
            'started_at' => now()->subHours(15),
            'ended_at' => null,
            'duration_seconds' => null,
        ]);

        $activeRecentEntry = TimeEntry::factory()->create([
            'user_id' => $user->id,
            'started_at' => now()->subHours(1),
            'ended_at' => null,
            'duration_seconds' => null,
        ]);

        $job = new CloseStaleTimersJob(maxHours: 12);
        $job->handle();

        $staleEntry->refresh();
        $activeRecentEntry->refresh();

        $this->assertNotNull($staleEntry->ended_at);
        $this->assertSame(12 * 3600, $staleEntry->duration_seconds);
        $this->assertNull($activeRecentEntry->ended_at);

        Notification::assertSentTo($user, LongRunningTimerNotification::class);
    }
}
