<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $colors = ['#3B82F6', '#10B981', '#6366F1', '#EC4899', '#F59E0B', '#8B5CF6'];

        return [
            'user_id' => User::factory(),
            'client_id' => null,
            'name' => fake()->words(2, true).' Project',
            'color' => fake()->randomElement($colors),
            'estimated_hours' => fake()->randomFloat(2, 10, 80),
            'status' => ProjectStatus::Active,
        ];
    }

    /**
     * Associate project with a client.
     */
    public function forClient(Client $client): static
    {
        return $this->state(fn () => [
            'client_id' => $client->id,
            'user_id' => $client->user_id,
        ]);
    }
}
