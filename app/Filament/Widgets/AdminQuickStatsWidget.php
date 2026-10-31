<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Services\Admin\AdminDashboardService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminQuickStatsWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        $service = app(AdminDashboardService::class);
        $counters = $service->getQuickCounters();

        return [
            Stat::make('Projets actifs', (string) $counters['active_projects'])
                ->description('En cours de réalisation')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('primary'),

            Stat::make('Clients', (string) $counters['clients'])
                ->description('Comptes clients enregistrés')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('primary'),

            Stat::make('Collaborateurs', (string) $counters['collaborators'])
                ->description('Membres de l’équipe')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Taux de facturation', $counters['billable_ratio'].' %')
                ->description('Temps imputable sur client')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
}
