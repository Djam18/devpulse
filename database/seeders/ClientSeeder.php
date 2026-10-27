<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
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

        $clients = [
            [
                'name' => 'Acme Corp',
                'color' => '#3B82F6',
                'hourly_rate' => 95.00,
            ],
            [
                'name' => 'Stark Innovations',
                'color' => '#8B5CF6',
                'hourly_rate' => 125.00,
            ],
            [
                'name' => 'Globex Corporation',
                'color' => '#10B981',
                'hourly_rate' => 85.00,
            ],
        ];

        foreach ($clients as $clientData) {
            Client::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'name' => $clientData['name'],
                ],
                $clientData
            );
        }
    }
}
