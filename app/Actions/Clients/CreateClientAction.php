<?php

declare(strict_types=1);

namespace App\Actions\Clients;

use App\DTOs\Clients\ClientData;
use App\Models\Client;
use App\Models\User;

final class CreateClientAction
{
    public function execute(User $user, ClientData $data): Client
    {
        return $user->clients()->create([
            'name' => $data->name,
            'color' => $data->color,
            'hourly_rate' => $data->hourlyRate,
        ]);
    }
}
