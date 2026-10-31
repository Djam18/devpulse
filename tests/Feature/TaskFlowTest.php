<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_redirected_from_tasks_page(): void
    {
        $response = $this->get(route('tasks.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_tasks_kanban_page(): void
    {
        $user = User::factory()->create();
        Project::factory()->count(2)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('tasks.index'));

        $response->assertOk();
    }

    public function test_guest_cannot_access_tasks_api(): void
    {
        $response = $this->getJson(route('api.v1.tasks.index'));

        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_access_versioned_v1_tasks_api(): void
    {
        $user = User::factory()->create();
        Task::factory()->count(2)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson(route('api.v1.tasks.index'));

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
    }

    public function test_user_can_list_only_their_tasks(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        Task::factory()->count(3)->create(['user_id' => $userA->id]);
        Task::factory()->count(2)->create(['user_id' => $userB->id]);

        $response = $this->actingAs($userA)->getJson(route('api.tasks.index'));

        $response->assertOk();
        $response->assertJsonCount(3, 'data');
    }

    public function test_user_can_create_a_task(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        $payload = [
            'title' => 'Implement Kanban drag-and-drop',
            'project_id' => $project->id,
            'description' => 'Add smooth animated column transitions',
            'priority' => TaskPriority::High->value,
            'status' => TaskStatus::Todo->value,
            'due_date' => now()->addDays(3)->format('Y-m-d'),
            'estimated_pomodoros' => 3,
        ];

        $response = $this->actingAs($user)->postJson(route('api.tasks.store'), $payload);

        $response->assertCreated();
        $response->assertJsonPath('data.title', 'Implement Kanban drag-and-drop');
        $response->assertJsonPath('data.priority', 'high');
        $response->assertJsonPath('data.status', 'todo');
        $response->assertJsonPath('data.project.id', $project->id);

        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'title' => 'Implement Kanban drag-and-drop',
            'priority' => 'high',
            'status' => 'todo',
        ]);
    }

    public function test_task_creation_rejects_foreign_user_project(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $foreignProject = Project::factory()->create(['user_id' => $userB->id]);

        $payload = [
            'title' => 'Attempting unauthorized project assignment',
            'project_id' => $foreignProject->id,
            'priority' => 'medium',
            'status' => 'todo',
        ];

        $response = $this->actingAs($userA)->postJson(route('api.tasks.store'), $payload);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['project_id']);
    }

    public function test_user_can_update_a_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Initial Title',
            'priority' => TaskPriority::Low,
            'status' => TaskStatus::Todo,
            'estimated_pomodoros' => 1,
        ]);

        $payload = [
            'title' => 'Updated Title',
            'priority' => TaskPriority::Urgent->value,
            'status' => TaskStatus::InProgress->value,
            'estimated_pomodoros' => 4,
            'description' => 'Updated description content',
        ];

        $response = $this->actingAs($user)->putJson(route('api.tasks.update', $task), $payload);

        $response->assertOk();
        $response->assertJsonPath('data.title', 'Updated Title');
        $response->assertJsonPath('data.priority', 'urgent');
        $response->assertJsonPath('data.status', 'in_progress');
        $response->assertJsonPath('data.estimated_pomodoros', 4);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Title',
            'priority' => 'urgent',
            'status' => 'in_progress',
        ]);
    }

    public function test_user_can_update_task_status_in_kanban(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'status' => TaskStatus::Todo,
        ]);

        $response = $this->actingAs($user)->patchJson(route('api.tasks.update-status', $task), [
            'status' => TaskStatus::Done->value,
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.status', 'done');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'done',
        ]);
    }

    public function test_user_cannot_update_another_users_task(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $userB->id]);

        $response = $this->actingAs($userA)->patchJson(route('api.tasks.update-status', $task), [
            'status' => TaskStatus::Done->value,
        ]);

        $response->assertForbidden();
    }

    public function test_user_can_delete_their_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson(route('api.tasks.destroy', $task));

        $response->assertNoContent();
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_user_cannot_delete_another_users_task(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $userB->id]);

        $response = $this->actingAs($userA)->deleteJson(route('api.tasks.destroy', $task));

        $response->assertForbidden();
        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }

    public function test_user_can_reorder_tasks_vertically(): void
    {
        $user = User::factory()->create();
        $task1 = Task::factory()->create(['user_id' => $user->id, 'status' => TaskStatus::Todo, 'order_column' => 0]);
        $task2 = Task::factory()->create(['user_id' => $user->id, 'status' => TaskStatus::Todo, 'order_column' => 1]);
        $task3 = Task::factory()->create(['user_id' => $user->id, 'status' => TaskStatus::Todo, 'order_column' => 2]);

        $response = $this->actingAs($user)->postJson(route('api.tasks.reorder'), [
            'task_ids' => [$task3->id, $task1->id, $task2->id],
            'status' => 'todo',
        ]);

        $response->assertOk();

        $this->assertEquals(0, $task3->fresh()->order_column);
        $this->assertEquals(1, $task1->fresh()->order_column);
        $this->assertEquals(2, $task2->fresh()->order_column);
    }

    public function test_reordering_does_not_affect_other_users_tasks(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $taskA = Task::factory()->create(['user_id' => $userA->id, 'order_column' => 5]);
        $taskB = Task::factory()->create(['user_id' => $userB->id, 'order_column' => 9]);

        $response = $this->actingAs($userA)->postJson(route('api.tasks.reorder'), [
            'task_ids' => [$taskB->id, $taskA->id],
        ]);

        $response->assertOk();

        // User A task is updated to index 1
        $this->assertEquals(1, $taskA->fresh()->order_column);
        // User B task remains untouched at index 9
        $this->assertEquals(9, $taskB->fresh()->order_column);
    }
}
