<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $colors = ['#EF4444', '#F59E0B', '#10B981', '#3B82F6', '#8B5CF6', '#EC4899', '#6B7280'];

        return [
            'user_id' => User::factory(),
            'name' => fake()->company(),
            'color' => fake()->randomElement($colors),
            'hourly_rate' => fake()->randomElement([50.00, 75.00, 95.00, 120.00, 150.00]),
        ];
    }
}
