<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TimeEntry>
 */
class TimeEntryFactory extends Factory
{
    protected $model = TimeEntry::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startedAt = now()->subMinutes(fake()->numberBetween(10, 180));
        $endedAt = (clone $startedAt)->addMinutes(fake()->numberBetween(10, 120));

        return [
            'user_id' => User::factory(),
            'project_id' => null,
            'task_id' => null,
            'description' => fake()->sentence(3),
            'started_at' => $startedAt,
            'ended_at' => $endedAt,
            'duration_seconds' => (int) $endedAt->diffInSeconds($startedAt),
            'is_pomodoro' => false,
        ];
    }

    /**
     * Indicate that the time entry is currently running.
     */
    public function running(): static
    {
        return $this->state(fn () => [
            'ended_at' => null,
            'duration_seconds' => null,
        ]);
    }
}
