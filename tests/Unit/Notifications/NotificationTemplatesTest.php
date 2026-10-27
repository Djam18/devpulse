<?php

declare(strict_types=1);

namespace Tests\Unit\Notifications;

use App\Models\TimeEntry;
use App\Models\User;
use App\Notifications\AbandonedTimerNotification;
use App\Notifications\WeeklySummaryNotification;
use App\Notifications\WelcomeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class NotificationTemplatesTest extends TestCase
{
    use RefreshDatabase;

    public function test_welcome_notification_renders_properly(): void
    {
        $user = User::factory()->create(['name' => 'John Doe']);
        $notification = new WelcomeNotification;
        $mail = $notification->toMail($user);

        $html = (string) $mail->render();

        $this->assertStringContainsString('Bienvenue, John Doe', $html);
        $this->assertStringContainsString('DevPulse', $html);
        $this->assertStringContainsString('⚡', $html);
    }

    public function test_abandoned_timer_notification_renders_properly(): void
    {
        $user = User::factory()->create(['name' => 'Sarah Connor']);
        $timeEntry = TimeEntry::factory()->create([
            'user_id' => $user->id,
            'started_at' => now()->subHours(5),
            'ended_at' => null,
            'description' => 'Fix Docker network bug',
        ]);

        $notification = new AbandonedTimerNotification($timeEntry);
        $mail = $notification->toMail($user);

        $html = (string) $mail->render();

        $this->assertStringContainsString('Fix Docker network bug', $html);
        $this->assertStringContainsString('5h', $html);
    }

    public function test_weekly_summary_notification_renders_properly(): void
    {
        $user = User::factory()->create(['name' => 'Alice Dev']);
        $notification = new WeeklySummaryNotification(
            totalHours: 37.5,
            pomodorosCount: 16,
            completedTasksCount: 8,
            topProjects: [
                ['name' => 'API v2', 'color' => '#7D2D68', 'hours' => 20.0],
                ['name' => 'Frontend Redesign', 'color' => '#3B82F6', 'hours' => 17.5],
            ]
        );

        $mail = $notification->toMail($user);
        $html = (string) $mail->render();

        $this->assertStringContainsString('37.5h', $html);
        $this->assertStringContainsString('16', $html);
        $this->assertStringContainsString('API v2', $html);
    }
}
