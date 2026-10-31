<?php

declare(strict_types=1);

namespace App\Actions\Clients;

use App\Models\Client;

final class DeleteClientAction
{
    public function execute(Client $client): void
    {
        $client->delete();
    }
}
