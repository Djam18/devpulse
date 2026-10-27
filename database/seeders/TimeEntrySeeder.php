<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TimeEntrySeeder extends Seeder
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

        $tickerTask = Task::where('user_id', $user->id)->where('title', 'like', '%real-time time entry ticker%')->first();
        $dbTask = Task::where('user_id', $user->id)->where('title', 'like', '%PostgreSQL schema%')->first();
        $benchmarkTask = Task::where('user_id', $user->id)->where('title', 'like', '%vector similarity%')->first();

        $entries = [
            // Yesterday morning - 2h 15m
            [
                'project_id' => $portalProject?->id,
                'task_id' => $dbTask?->id,
                'description' => 'Drafting and applying PostgreSQL schema migrations',
                'started_at' => Carbon::yesterday()->setHour(9)->setMinute(15),
                'ended_at' => Carbon::yesterday()->setHour(11)->setMinute(30),
                'is_pomodoro' => false,
            ],
            // Yesterday afternoon - 1h 45m
            [
                'project_id' => $aiProject?->id,
                'task_id' => $benchmarkTask?->id,
                'description' => 'Setting up pgvector benchmark harnesses and seeds',
                'started_at' => Carbon::yesterday()->setHour(14)->setMinute(0),
                'ended_at' => Carbon::yesterday()->setHour(15)->setMinute(45),
                'is_pomodoro' => false,
            ],
            // 2 days ago - Pomodoro focus session 25m
            [
                'project_id' => $inventoryProject?->id,
                'task_id' => null,
                'description' => 'Reviewing warehouse inventory webhook specs',
                'started_at' => Carbon::now()->subDays(2)->setHour(10)->setMinute(0),
                'ended_at' => Carbon::now()->subDays(2)->setHour(10)->setMinute(25),
                'is_pomodoro' => true,
            ],
            // 3 days ago - 3 hours
            [
                'project_id' => $portalProject?->id,
                'task_id' => null,
                'description' => 'Customer portal architecture alignment meeting and wireframing',
                'started_at' => Carbon::now()->subDays(3)->setHour(13)->setMinute(0),
                'ended_at' => Carbon::now()->subDays(3)->setHour(16)->setMinute(0),
                'is_pomodoro' => false,
            ],
            // Active timer running right now (started 35 minutes ago)
            [
                'project_id' => $portalProject?->id,
                'task_id' => $tickerTask?->id,
                'description' => 'Building Vue 3 ActiveTimerBar component and Pinia store',
                'started_at' => Carbon::now()->subMinutes(35),
                'ended_at' => null,
                'is_pomodoro' => false,
            ],
        ];

        foreach ($entries as $entry) {
            $startedAt = $entry['started_at'];
            $endedAt = $entry['ended_at'];
            $durationSeconds = $endedAt !== null
                ? abs((int) $endedAt->diffInSeconds($startedAt))
                : null;

            TimeEntry::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'started_at' => $startedAt,
                ],
                [
                    'user_id' => $user->id,
                    'project_id' => $entry['project_id'],
                    'task_id' => $entry['task_id'],
                    'description' => $entry['description'],
                    'started_at' => $startedAt,
                    'ended_at' => $endedAt,
                    'duration_seconds' => $durationSeconds,
                    'is_pomodoro' => $entry['is_pomodoro'],
                ]
            );
        }
    }
}
