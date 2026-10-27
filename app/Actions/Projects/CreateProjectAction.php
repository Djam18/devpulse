<?php

declare(strict_types=1);

namespace App\Actions\Projects;

use App\DTOs\Projects\ProjectData;
use App\Models\Project;
use App\Models\User;

final class CreateProjectAction
{
    public function execute(User $user, ProjectData $data): Project
    {
        return Project::create([
            'user_id' => $user->id,
            'client_id' => $data->clientId,
            'name' => $data->name,
            'color' => $data->color,
            'estimated_hours' => $data->estimatedHours,
            'status' => $data->status,
        ]);
    }
}
