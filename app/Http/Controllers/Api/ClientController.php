<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Clients\CreateClientAction;
use App\Actions\Clients\DeleteClientAction;
use App\Actions\Clients\UpdateClientAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Clients\StoreClientRequest;
use App\Http\Requests\Clients\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class ClientController extends Controller
{
    /**
     * Display a listing of the user's clients.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        /** @var User $user */
        $user = $request->user();

        $clients = $user->clients()
            ->withCount('projects')
            ->orderBy('name')
            ->get();

        return ClientResource::collection($clients);
    }

    /**
     * Store a newly created client.
     */
    public function store(StoreClientRequest $request, CreateClientAction $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $client = $action->execute($user, $request->toData());

        return (new ClientResource($client->loadCount('projects')))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified client.
     */
    public function show(Client $client): ClientResource
    {
        Gate::authorize('view', $client);

        return new ClientResource($client->loadCount('projects'));
    }

    /**
     * Update the specified client.
     */
    public function update(
        UpdateClientRequest $request,
        Client $client,
        UpdateClientAction $action
    ): ClientResource {
        Gate::authorize('update', $client);

        $updated = $action->execute($client, $request->toData());

        return new ClientResource($updated->loadCount('projects'));
    }

    /**
     * Remove the specified client.
     */
    public function destroy(Client $client, DeleteClientAction $action): JsonResponse
    {
        Gate::authorize('delete', $client);

        $clientName = $client->name;
        $action->execute($client);

        return response()->json([
            'message' => "Le client « {$clientName} » a été supprimé avec succès.",
        ]);
    }
}
