<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Enums\ProjectStatus;
use App\Models\Client;
use App\Models\Project;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class AdminDashboardService
{
    /**
     * Compute potential committed revenue for current month (hours pointed * client hourly rate).
     */
    public function getCommittedRevenueThisMonth(): float
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        /** @var Collection<int, TimeEntry> $entries */
        $entries = TimeEntry::query()
            ->completed()
            ->whereBetween('started_at', [$startOfMonth, $endOfMonth])
            ->with(['project.client'])
            ->get();

        $revenue = 0.0;
        foreach ($entries as $entry) {
            $hours = ($entry->duration_seconds ?? 0) / 3600.0;
            $rate = (float) ($entry->project?->client?->hourly_rate ?? 0.0);
            $revenue += ($hours * $rate);
        }

        return round($revenue, 2);
    }

    /**
     * Total hours worked in the last 30 days.
     */
    public function getWorkedHoursLast30Days(): float
    {
        $since = now()->subDays(30);

        $totalSeconds = (int) TimeEntry::query()
            ->completed()
            ->where('started_at', '>=', $since)
            ->sum('duration_seconds');

        return round($totalSeconds / 3600.0, 1);
    }

    /**
     * Estimate capacity usage percentage based on active collaborators (140h monthly baseline per person).
     */
    public function getCapacityUsagePercentage(float $workedHours): int
    {
        $collaboratorsCount = User::query()->count();
        if ($collaboratorsCount === 0) {
            return 0;
        }

        $baselineCapacity = $collaboratorsCount * 140.0;
        if ($baselineCapacity <= 0) {
            return 0;
        }

        $percent = ($workedHours / $baselineCapacity) * 100.0;

        return (int) min(round($percent), 100);
    }

    /**
     * Number of active projects exceeding their budget (> 100% burn rate).
     */
    public function getOverrunProjectsCount(): int
    {
        return Project::query()
            ->where('status', ProjectStatus::Active)
            ->whereNotNull('estimated_hours')
            ->where('estimated_hours', '>', 0)
            ->whereHas('timeEntries')
            ->get()
            ->filter(fn (Project $p): bool => ($p->burnRate() ?? 0.0) > 100.0)
            ->count();
    }

    /**
     * Query projects that have budget allocations, eager loading relations.
     *
     * @return Builder<Project>
     */
    public function getOverrunProjectsQuery(): Builder
    {
        return Project::query()
            ->whereNotNull('estimated_hours')
            ->where('estimated_hours', '>', 0)
            ->with(['client', 'user', 'timeEntries'])
            ->latest('updated_at');
    }

    /**
     * Return quick summary counters.
     *
     * @return array{active_projects: int, clients: int, collaborators: int, billable_ratio: int}
     */
    public function getQuickCounters(): array
    {
        $activeProjects = Project::query()->where('status', ProjectStatus::Active)->count();
        $clients = Client::query()->count();
        $collaborators = User::query()->count();

        // Calculate billable percentage: entries with a client / all entries
        $totalEntries = TimeEntry::query()->completed()->count();
        $billableEntries = TimeEntry::query()
            ->completed()
            ->whereHas('project', fn (Builder $q) => $q->whereNotNull('client_id'))
            ->count();

        $billableRatio = $totalEntries > 0
            ? (int) round(($billableEntries / $totalEntries) * 100)
            : 90;

        return [
            'active_projects' => $activeProjects,
            'clients' => $clients,
            'collaborators' => $collaborators,
            'billable_ratio' => $billableRatio,
        ];
    }
}
