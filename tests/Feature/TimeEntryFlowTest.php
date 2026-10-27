<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimeEntryFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_time_entries_api(): void
    {
        $response = $this->getJson(route('api.v1.time-entries.active'));

        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_access_versioned_v1_api(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson(route('api.v1.time-entries.active'));

        $response->assertOk();
        $this->assertNull($response->json('data'));
    }

    public function test_authenticated_user_returns_empty_when_no_active_timer(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson(route('api.time-entries.active'));

        $response->assertOk();
        $this->assertNull($response->json('data'));
    }

    public function test_user_can_start_a_live_timer(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('api.time-entries.start'), [
            'description' => 'Working on auth refactor',
        ]);

        $response->assertCreated();
        $response->assertJsonPath('data.description', 'Working on auth refactor');
        $response->assertJsonPath('data.is_running', true);

        $this->assertDatabaseHas('time_entries', [
            'user_id' => $user->id,
            'description' => 'Working on auth refactor',
            'ended_at' => null,
        ]);
    }

    public function test_starting_new_timer_automatically_closes_the_previous_active_timer(): void
    {
        $user = User::factory()->create();

        // Create an existing timer started 45 minutes ago
        $firstTimer = TimeEntry::factory()->create([
            'user_id' => $user->id,
            'description' => 'First task',
            'started_at' => now()->subMinutes(45),
            'ended_at' => null,
            'duration_seconds' => null,
        ]);

        // Start a second timer
        $response = $this->actingAs($user)->postJson(route('api.time-entries.start'), [
            'description' => 'Second task',
        ]);

        $response->assertCreated();

        // Assert first timer is now closed and duration calculated
        $firstTimer->refresh();
        $this->assertNotNull($firstTimer->ended_at);
        $this->assertGreaterThanOrEqual(2700, $firstTimer->duration_seconds);

        // Assert second timer is active
        $this->assertDatabaseHas('time_entries', [
            'user_id' => $user->id,
            'description' => 'Second task',
            'ended_at' => null,
        ]);
    }

    public function test_user_can_stop_a_running_timer(): void
    {
        $user = User::factory()->create();

        $timer = TimeEntry::factory()->create([
            'user_id' => $user->id,
            'description' => 'Sprint planning',
            'started_at' => now()->subMinutes(30),
            'ended_at' => null,
        ]);

        $response = $this->actingAs($user)->patchJson(route('api.time-entries.stop', $timer));

        $response->assertOk();
        $response->assertJsonPath('data.is_running', false);
        $this->assertGreaterThanOrEqual(1800, $response->json('data.duration_seconds'));

        $timer->refresh();
        $this->assertNotNull($timer->ended_at);
        $this->assertGreaterThanOrEqual(1800, $timer->duration_seconds);
    }

    public function test_user_cannot_stop_another_users_timer(): void
    {
        $owner = User::factory()->create();
        $attacker = User::factory()->create();

        $timer = TimeEntry::factory()->create([
            'user_id' => $owner->id,
            'started_at' => now()->subMinutes(10),
            'ended_at' => null,
        ]);

        $response = $this->actingAs($attacker)->patchJson(route('api.time-entries.stop', $timer));

        $response->assertForbidden();
    }

    public function test_user_can_log_retroactive_manual_time(): void
    {
        $user = User::factory()->create();

        $start = now()->subHours(3);
        $end = now()->subHours(1);

        $response = $this->actingAs($user)->postJson(route('api.time-entries.store'), [
            'started_at' => $start->toIso8601String(),
            'ended_at' => $end->toIso8601String(),
            'description' => 'Deep architecture review',
        ]);

        $response->assertCreated();
        $response->assertJsonPath('data.description', 'Deep architecture review');
        $this->assertEquals(7200, $response->json('data.duration_seconds'));

        $this->assertDatabaseHas('time_entries', [
            'user_id' => $user->id,
            'description' => 'Deep architecture review',
            'duration_seconds' => 7200,
        ]);
    }

    public function test_rapid_consecutive_timer_starts_guarantee_single_active_timer_per_user(): void
    {
        $user = User::factory()->create();

        // Start 3 timers in rapid succession
        $this->actingAs($user)->postJson(route('api.time-entries.start'), ['description' => 'Task 1'])->assertCreated();
        $this->actingAs($user)->postJson(route('api.time-entries.start'), ['description' => 'Task 2'])->assertCreated();
        $this->actingAs($user)->postJson(route('api.time-entries.start'), ['description' => 'Task 3'])->assertCreated();

        // Exactly one timer must have ended_at === null
        $activeCount = TimeEntry::query()
            ->where('user_id', $user->id)
            ->whereNull('ended_at')
            ->count();

        $this->assertSame(1, $activeCount);

        $activeTimer = TimeEntry::query()
            ->where('user_id', $user->id)
            ->whereNull('ended_at')
            ->first();

        $this->assertSame('Task 3', $activeTimer?->description);
    }
}
