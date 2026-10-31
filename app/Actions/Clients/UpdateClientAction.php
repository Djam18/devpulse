<?php

declare(strict_types=1);

namespace App\Actions\Clients;

use App\DTOs\Clients\ClientData;
use App\Models\Client;

final class UpdateClientAction
{
    public function execute(Client $client, ClientData $data): Client
    {
        $client->update([
            'name' => $data->name,
            'color' => $data->color,
            'hourly_rate' => $data->hourlyRate,
        ]);

        return $client->fresh();
    }
}
