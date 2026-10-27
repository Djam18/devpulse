<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
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

        $portalProject = Project::where('user_id', $user->id)->where('name', 'Customer Portal Redesign')->first();
        $aiProject = Project::where('user_id', $user->id)->where('name', 'AI Analytics Pipeline')->first();
        $inventoryProject = Project::where('user_id', $user->id)->where('name', 'Inventory Management API')->first();
        $portfolioProject = Project::where('user_id', $user->id)->where('name', 'Personal Portfolio & Blog')->first();

        $tasks = [
            [
                'title' => 'Design billing invoice PDF export',
                'project_id' => $portalProject?->id,
                'description' => 'Draft the monthly client timesheet layout and PDF generation pipeline.',
                'priority' => TaskPriority::Medium,
                'status' => TaskStatus::Todo,
                'due_date' => now()->addDays(5)->format('Y-m-d'),
                'estimated_pomodoros' => 3,
                'completed_pomodoros' => 0,
            ],
            [
                'title' => 'Configure OAuth2 multi-factor authentication',
                'project_id' => $aiProject?->id,
                'description' => 'Integrate WebAuthn and TOTP challenge step on user login.',
                'priority' => TaskPriority::High,
                'status' => TaskStatus::Todo,
                'due_date' => now()->addDays(3)->format('Y-m-d'),
                'estimated_pomodoros' => 4,
                'completed_pomodoros' => 0,
            ],
            [
                'title' => 'Write OpenAPI 3.1 contract specification',
                'project_id' => $inventoryProject?->id,
                'description' => 'Define endpoints, schemas and security schemes for external warehouse sync.',
                'priority' => TaskPriority::Low,
                'status' => TaskStatus::Todo,
                'due_date' => now()->addDays(8)->format('Y-m-d'),
                'estimated_pomodoros' => 2,
                'completed_pomodoros' => 0,
            ],
            [
                'title' => 'Implement real-time time entry ticker component',
                'project_id' => $portalProject?->id,
                'description' => 'Build Vue 3 active timer bar with start/stop toggle and drift-free math.',
                'priority' => TaskPriority::High,
                'status' => TaskStatus::InProgress,
                'due_date' => now()->addDays(1)->format('Y-m-d'),
                'estimated_pomodoros' => 2,
                'completed_pomodoros' => 1,
            ],
            [
                'title' => 'Benchmark vector similarity search latency',
                'project_id' => $aiProject?->id,
                'description' => 'Profile pgvector HNSW index queries under 50 concurrent requests.',
                'priority' => TaskPriority::Urgent,
                'status' => TaskStatus::InProgress,
                'due_date' => now()->addDays(2)->format('Y-m-d'),
                'estimated_pomodoros' => 5,
                'completed_pomodoros' => 2,
            ],
            [
                'title' => 'Initialize PostgreSQL schema and migrations',
                'project_id' => $portalProject?->id,
                'description' => 'Create clients, projects, tasks, and time_entries tables with proper foreign keys.',
                'priority' => TaskPriority::High,
                'status' => TaskStatus::Done,
                'due_date' => now()->subDays(2)->format('Y-m-d'),
                'estimated_pomodoros' => 2,
                'completed_pomodoros' => 2,
            ],
            [
                'title' => 'Setup Tailwind CSS 3 and dark mode support',
                'project_id' => $portfolioProject?->id,
                'description' => 'Configure theme palette, typography styles, and dark class switching.',
                'priority' => TaskPriority::Medium,
                'status' => TaskStatus::Done,
                'due_date' => now()->subDays(1)->format('Y-m-d'),
                'estimated_pomodoros' => 1,
                'completed_pomodoros' => 1,
            ],
        ];

        foreach ($tasks as $taskData) {
            Task::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'title' => $taskData['title'],
                ],
                $taskData
            );
        }
    }
}
