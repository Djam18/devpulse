<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_dashboard_with_metrics(): void
    {
        $user = User::factory()->create();

        $project = Project::factory()->create([
            'user_id' => $user->id,
            'name' => 'API Optimization',
            'color' => '#7D2D68',
        ]);

        $task = Task::factory()->create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'completed_pomodoros' => 3,
        ]);

        TimeEntry::factory()->create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'task_id' => $task->id,
            'started_at' => now()->startOfWeek()->addHours(2),
            'ended_at' => now()->startOfWeek()->addHours(4),
            'duration_seconds' => 7200, // 2 hours
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Dashboard')
            ->has('metrics')
            ->has('dailyActivity')
            ->has('projectBreakdown')
            ->where('metrics.totalWeekHours', 2)
            ->where('metrics.estimatedRevenue', 170) // 2h * 85€
            ->where('metrics.completedPomodoros', 3)
        );
    }

    public function test_dashboard_calculates_estimated_revenue_using_client_hourly_rate(): void
    {
        $user = User::factory()->create();

        $client = Client::factory()->create([
            'user_id' => $user->id,
            'name' => 'Acme Corp',
            'hourly_rate' => 125.0,
        ]);

        $project = Project::factory()->create([
            'user_id' => $user->id,
            'client_id' => $client->id,
            'name' => 'Billing System',
        ]);

        TimeEntry::factory()->create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'started_at' => now()->startOfWeek()->addHours(1),
            'ended_at' => now()->startOfWeek()->addHours(5),
            'duration_seconds' => 14400, // 4 hours
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Dashboard')
            ->where('metrics.totalWeekHours', 4)
            ->where('metrics.estimatedRevenue', 500) // 4 hours * 125€/h = 500€
        );
    }
}
