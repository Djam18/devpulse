<?php

declare(strict_types=1);

namespace App\Actions\Projects;

use App\DTOs\Projects\ProjectData;
use App\Models\Project;

final class UpdateProjectAction
{
    public function execute(Project $project, ProjectData $data): Project
    {
        $project->update([
            'client_id' => $data->clientId,
            'name' => $data->name,
            'color' => $data->color,
            'estimated_hours' => $data->estimatedHours,
            'status' => $data->status,
        ]);

        return $project;
    }
}
