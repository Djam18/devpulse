<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ProjectStatus;
use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'alex@devpulse.io')->first();

        if (! $user) {
            return;
        }

        $acme = Client::where('user_id', $user->id)->where('name', 'Acme Corp')->first();
        $stark = Client::where('user_id', $user->id)->where('name', 'Stark Innovations')->first();
        $globex = Client::where('user_id', $user->id)->where('name', 'Globex Corporation')->first();

        $projects = [
            [
                'name' => 'Customer Portal Redesign',
                'client_id' => $acme?->id,
                'color' => '#3B82F6',
                'estimated_hours' => 40.00,
                'status' => ProjectStatus::Active,
            ],
            [
                'name' => 'AI Analytics Pipeline',
                'client_id' => $stark?->id,
                'color' => '#8B5CF6',
                'estimated_hours' => 60.00,
                'status' => ProjectStatus::Active,
            ],
            [
                'name' => 'Inventory Management API',
                'client_id' => $globex?->id,
                'color' => '#10B981',
                'estimated_hours' => 30.00,
                'status' => ProjectStatus::Active,
            ],
            [
                'name' => 'Personal Portfolio & Blog',
                'client_id' => null,
                'color' => '#F59E0B',
                'estimated_hours' => 15.00,
                'status' => ProjectStatus::Active,
            ],
        ];

        foreach ($projects as $projectData) {
            Project::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'name' => $projectData['name'],
                ],
                $projectData
            );
        }
    }
}
