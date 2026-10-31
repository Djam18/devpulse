<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Resources\ProjectResource;
use App\Models\Project;
use App\Services\Admin\AdminDashboardService;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class BudgetOverrunProjectsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Suivi du Burn Rate des Projets';

    public function table(Table $table): Table
    {
        $service = app(AdminDashboardService::class);

        return $table
            ->query($service->getOverrunProjectsQuery())
            ->columns([
                Tables\Columns\ColorColumn::make('color')
                    ->label('')
                    ->width('30px'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Projet')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('client.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Aucun client'),

                Tables\Columns\TextColumn::make('estimated_hours')
                    ->label('Budget alloué')
                    ->suffix(' h')
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('consumed')
                    ->label('Consommé')
                    ->getStateUsing(fn (Project $record): string => $record->consumedHours().' h')
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('burn_rate')
                    ->label('Burn Rate')
                    ->getStateUsing(fn (Project $record): ?string => $record->burnRate() !== null ? $record->burnRate().' %' : '—')
                    ->badge()
                    ->color(fn (Project $record): string => match (true) {
                        $record->burnRate() === null => 'gray',
                        $record->burnRate() > 100 => 'danger',
                        $record->burnRate() >= 80 => 'warning',
                        default => 'success',
                    })
                    ->alignEnd(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Détails')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(fn (Project $record): string => ProjectResource::getUrl('index')),
            ])
            ->paginated([5, 10]);
    }
}
