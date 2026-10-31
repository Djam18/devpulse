<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\ProjectStatus;
use App\Models\Client;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\User;
use App\Services\Admin\AdminDashboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_metrics_calculate_revenue_correctly(): void
    {
        $user = User::factory()->admin()->create();

        $client = Client::factory()->create([
            'user_id' => $user->id,
            'hourly_rate' => 100.00,
        ]);

        $project = Project::factory()->create([
            'user_id' => $user->id,
            'client_id' => $client->id,
            'estimated_hours' => 10,
            'status' => ProjectStatus::Active,
        ]);

        // Create completed time entry of 2 hours (7200 seconds) in current month
        TimeEntry::factory()->create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'started_at' => now()->startOfMonth()->addDays(2),
            'ended_at' => now()->startOfMonth()->addDays(2)->addHours(2),
            'duration_seconds' => 7200,
        ]);

        $service = app(AdminDashboardService::class);
        $revenue = $service->getCommittedRevenueThisMonth();
        $workedHours = $service->getWorkedHoursLast30Days();

        $this->assertEquals(200.00, $revenue);
        $this->assertEquals(2.0, $workedHours);
    }

    public function test_admin_dashboard_detects_overrun_projects(): void
    {
        $user = User::factory()->admin()->create();

        $project = Project::factory()->create([
            'user_id' => $user->id,
            'estimated_hours' => 5,
            'status' => ProjectStatus::Active,
        ]);

        // Consumed 6 hours (21600 seconds) -> 120% burn rate
        TimeEntry::factory()->create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'started_at' => now()->subDay(),
            'ended_at' => now()->subDay()->addHours(6),
            'duration_seconds' => 21600,
        ]);

        $service = app(AdminDashboardService::class);

        $this->assertEquals(1, $service->getOverrunProjectsCount());
        $this->assertEquals(120.0, $project->burnRate());
    }

    public function test_admin_can_access_filament_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertOk();
        $response->assertSee('DevPulse');
    }

    public function test_admin_can_access_all_filament_resources(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/admin/users')->assertOk();
        $this->actingAs($admin)->get('/admin/clients')->assertOk();
        $this->actingAs($admin)->get('/admin/projects')->assertOk();
        $this->actingAs($admin)->get('/admin/time-entries')->assertOk();
    }

    public function test_non_admin_cannot_access_filament_resources(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)->get('/admin/users')->assertForbidden();
        $this->actingAs($user)->get('/admin/clients')->assertForbidden();
        $this->actingAs($user)->get('/admin/projects')->assertForbidden();
        $this->actingAs($user)->get('/admin/time-entries')->assertForbidden();
    }
}
