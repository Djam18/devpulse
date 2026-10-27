<?php

declare(strict_types=1);

namespace Tests\Unit\Actions\Projects;

use App\Actions\Projects\CreateProjectAction;
use App\DTOs\Projects\ProjectData;
use App\Enums\ProjectStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CreateProjectActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_project_scoped_to_user(): void
    {
        $user = User::factory()->create();

        $action = new CreateProjectAction;
        $data = new ProjectData(
            name: 'API Redesign',
            color: '#7D2D68',
            estimatedHours: 40.0,
            status: ProjectStatus::Active
        );

        $project = $action->execute($user, $data);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'user_id' => $user->id,
            'name' => 'API Redesign',
            'color' => '#7D2D68',
            'status' => 'active',
        ]);
    }
}
