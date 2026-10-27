<?php

declare(strict_types=1);

namespace App\Actions\Dashboard;

use App\Models\TimeEntry;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

final class GetDashboardMetricsAction
{
    private const DEFAULT_HOURLY_RATE = 85.0;

    private const WEEKLY_GOAL_HOURS = 35.0;

    private const DEFAULT_PROJECT_COLOR = '#7D2D68';

    private const ROLLING_DAYS_COUNT = 7;

    /**
     * Orchestrates the retrieval and aggregation of dashboard data.
     *
     * @return array{
     *     metrics: array{
     *         totalWeekHours: float,
     *         weeklyGoalHours: float,
     *         estimatedRevenue: float,
     *         currency: string,
     *         completedPomodoros: int
     *     },
     *     dailyActivity: list<array{day: string, date: string, hours: float}>,
     *     projectBreakdown: list<array{id: int|string|null, name: string, color: string, hours: float, percentage: float|int}>
     * }
     */
    public function execute(User $user): array
    {
        $weekEntries = $this->fetchWeekTimeEntries($user);
        $totalWeekSeconds = (int) $weekEntries->sum('duration_seconds');
        $totalWeekHours = round($totalWeekSeconds / 3600, 1);
        $pomodoroCount = (int) $user->tasks()->sum('completed_pomodoros');
        $estimatedRevenue = $this->calculateEstimatedRevenue($weekEntries);

        return [
            'metrics' => $this->buildMetricsSummary($totalWeekHours, $pomodoroCount, $estimatedRevenue),
            'dailyActivity' => $this->buildDailyRollingActivity($user),
            'projectBreakdown' => $this->buildProjectBreakdown($weekEntries, $totalWeekSeconds),
        ];
    }

    /**
     * Retrieve finished time entries for the current week.
     *
     * @return Collection<int, TimeEntry>
     */
    private function fetchWeekTimeEntries(User $user): Collection
    {
        return $user->timeEntries()
            ->with(['project.client'])
            ->whereNotNull('ended_at')
            ->whereBetween('started_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->get();
    }

    /**
     * Calculate dynamic estimated revenue based on linked client hourly rates.
     *
     * @param  Collection<int, TimeEntry>  $weekEntries
     */
    private function calculateEstimatedRevenue(Collection $weekEntries): float
    {
        $revenue = 0.0;

        foreach ($weekEntries as $entry) {
            $hours = ((int) $entry->duration_seconds) / 3600;
            $hourlyRate = $entry->project?->client?->hourly_rate !== null
                ? (float) $entry->project->client->hourly_rate
                : self::DEFAULT_HOURLY_RATE;

            $revenue += ($hours * $hourlyRate);
        }

        return round($revenue, 2);
    }

    /**
     * Assemble top-level summary metrics.
     *
     * @return array{totalWeekHours: float, weeklyGoalHours: float, estimatedRevenue: float, currency: string, completedPomodoros: int}
     */
    private function buildMetricsSummary(float $totalWeekHours, int $pomodoroCount, float $estimatedRevenue): array
    {
        return [
            'totalWeekHours' => $totalWeekHours,
            'weeklyGoalHours' => self::WEEKLY_GOAL_HOURS,
            'estimatedRevenue' => $estimatedRevenue,
            'currency' => 'EUR',
            'completedPomodoros' => $pomodoroCount,
        ];
    }

    /**
     * Calculate daily duration on a 7-day rolling window.
     *
     * @return list<array{day: string, date: string, hours: float}>
     */
    private function buildDailyRollingActivity(User $user): array
    {
        $dailyActivity = [];

        for ($offset = self::ROLLING_DAYS_COUNT - 1; $offset >= 0; $offset--) {
            $date = Carbon::today()->subDays($offset);
            $daySeconds = (int) $user->timeEntries()
                ->whereNotNull('ended_at')
                ->whereDate('started_at', $date)
                ->sum('duration_seconds');

            $dailyActivity[] = [
                'day' => $date->format('D'),
                'date' => $date->format('Y-m-d'),
                'hours' => round($daySeconds / 3600, 1),
            ];
        }

        return $dailyActivity;
    }

    /**
     * Calculate project share and percentage breakdown.
     *
     * @param  Collection<int, TimeEntry>  $weekEntries
     * @return list<array{id: int|string|null, name: string, color: string, hours: float, percentage: float|int}>
     */
    private function buildProjectBreakdown(Collection $weekEntries, int $totalWeekSeconds): array
    {
        $breakdown = [];

        foreach ($weekEntries->groupBy('project_id') as $projectId => $entries) {
            $firstEntry = $entries->first();
            $seconds = (int) $entries->sum('duration_seconds');

            $breakdown[] = [
                'id' => $projectId,
                'name' => $firstEntry?->project?->name ?? 'General / No Project',
                'color' => $firstEntry?->project?->color ?? self::DEFAULT_PROJECT_COLOR,
                'hours' => round($seconds / 3600, 1),
                'percentage' => $totalWeekSeconds > 0 ? round(($seconds / $totalWeekSeconds) * 100) : 0,
            ];
        }

        return $breakdown;
    }
}
