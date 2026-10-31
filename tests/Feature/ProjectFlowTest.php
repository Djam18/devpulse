<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_clients_or_projects(): void
    {
        $this->getJson(route('api.v1.clients.index'))->assertUnauthorized();
        $this->getJson(route('api.v1.projects.index'))->assertUnauthorized();
    }

    public function test_user_can_create_and_list_clients(): void
    {
        $user = User::factory()->create();

        $payload = [
            'name' => 'Acme Corp',
            'color' => '#10B981',
            'hourly_rate' => 85.50,
        ];

        $response = $this->actingAs($user)->postJson(route('api.v1.clients.store'), $payload);

        $response->assertCreated();
        $response->assertJsonPath('data.name', 'Acme Corp');
        $response->assertJsonPath('data.hourly_rate', 85.50);

        $this->assertDatabaseHas('clients', [
            'user_id' => $user->id,
            'name' => 'Acme Corp',
            'hourly_rate' => 85.50,
        ]);

        $list = $this->actingAs($user)->getJson(route('api.v1.clients.index'));
        $list->assertOk();
        $list->assertJsonCount(1, 'data');
    }

    public function test_user_can_create_project_with_client(): void
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);

        $payload = [
            'name' => 'Mobile App v2',
            'color' => '#8B5CF6',
            'client_id' => $client->id,
            'estimated_hours' => 120.0,
            'status' => 'active',
        ];

        $response = $this->actingAs($user)->postJson(route('api.v1.projects.store'), $payload);

        $response->assertCreated();
        $response->assertJsonPath('data.name', 'Mobile App v2');
        $response->assertJsonPath('data.client.id', $client->id);
        $response->assertJsonPath('data.estimated_hours', 120);

        $this->assertDatabaseHas('projects', [
            'user_id' => $user->id,
            'client_id' => $client->id,
            'name' => 'Mobile App v2',
            'status' => 'active',
        ]);
    }

    public function test_user_cannot_attach_foreign_client_to_project(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $clientB = Client::factory()->create(['user_id' => $userB->id]);

        $payload = [
            'name' => 'Hacked Project',
            'client_id' => $clientB->id,
        ];

        $response = $this->actingAs($userA)->postJson(route('api.v1.projects.store'), $payload);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['client_id']);
    }

    public function test_user_cannot_update_or_delete_another_users_project(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $projectB = Project::factory()->create(['user_id' => $userB->id]);

        $updateResponse = $this->actingAs($userA)->putJson(route('api.v1.projects.update', $projectB), [
            'name' => 'Malicious Rename',
        ]);
        $updateResponse->assertForbidden();

        $deleteResponse = $this->actingAs($userA)->deleteJson(route('api.v1.projects.destroy', $projectB));
        $deleteResponse->assertForbidden();

        $this->assertDatabaseHas('projects', ['id' => $projectB->id]);
    }

    public function test_user_can_access_projects_inertia_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('projects.index'));

        $response->assertOk();
    }
}
