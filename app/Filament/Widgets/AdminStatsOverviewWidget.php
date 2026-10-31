<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Services\Admin\AdminDashboardService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $service = app(AdminDashboardService::class);

        $revenue = $service->getCommittedRevenueThisMonth();
        $workedHours = $service->getWorkedHoursLast30Days();
        $capacityPercent = $service->getCapacityUsagePercentage($workedHours);
        $overrunCount = $service->getOverrunProjectsCount();

        return [
            Stat::make('CA potentiel engagé', number_format($revenue, 2, ',', ' ').' €')
                ->description('Mois en cours (heures × taux horaire)')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Heures travaillées', number_format($workedHours, 1, ',', ' ').' h')
                ->description("Sur les 30 derniers jours ({$capacityPercent} % de capacité)")
                ->descriptionIcon('heroicon-m-clock')
                ->color('primary'),

            Stat::make('Projets en dépassement', (string) $overrunCount)
                ->description('Burn Rate > 100 % (Attention requise)')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($overrunCount > 0 ? 'danger' : 'success'),
        ];
    }
}
