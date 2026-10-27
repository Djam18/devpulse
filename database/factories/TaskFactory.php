<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'project_id' => null,
            'title' => fake()->sentence(4),
            'description' => fake()->boolean(70) ? fake()->paragraph() : null,
            'priority' => fake()->randomElement(TaskPriority::cases()),
            'status' => fake()->randomElement(TaskStatus::cases()),
            'due_date' => fake()->boolean(60) ? now()->addDays(fake()->numberBetween(1, 14))->format('Y-m-d') : null,
            'estimated_pomodoros' => fake()->numberBetween(1, 6),
            'completed_pomodoros' => fake()->numberBetween(0, 2),
        ];
    }

    /**
     * Indicate that the task is in todo status.
     */
    public function todo(): static
    {
        return $this->state(fn () => [
            'status' => TaskStatus::Todo,
            'completed_pomodoros' => 0,
        ]);
    }

    /**
     * Indicate that the task is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn () => [
            'status' => TaskStatus::InProgress,
        ]);
    }

    /**
     * Indicate that the task is done.
     */
    public function done(): static
    {
        return $this->state(fn () => [
            'status' => TaskStatus::Done,
            'completed_pomodoros' => fake()->numberBetween(1, 5),
        ]);
    }

    /**
     * Associate task with a project.
     */
    public function forProject(Project $project): static
    {
        return $this->state(fn () => [
            'project_id' => $project->id,
            'user_id' => $project->user_id,
        ]);
    }
}
