<?php

namespace App\Filament\Resources;

use App\Enums\ProjectStatus;
use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Projets';

    protected static ?string $modelLabel = 'Projet';

    protected static ?string $pluralModelLabel = 'Projets';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Collaborateur')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('client_id')
                    ->label('Client associé')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Forms\Components\TextInput::make('name')
                    ->label('Nom du projet')
                    ->required()
                    ->maxLength(255),
                Forms\Components\ColorPicker::make('color')
                    ->label('Couleur')
                    ->default('#7D2D68')
                    ->required(),
                Forms\Components\TextInput::make('estimated_hours')
                    ->label('Budget prévisionnel')
                    ->numeric()
                    ->suffix('h')
                    ->minValue(0)
                    ->step(0.50),
                Forms\Components\Select::make('status')
                    ->label('Statut')
                    ->options([
                        ProjectStatus::Active->value => 'Actif',
                        ProjectStatus::Completed->value => 'Terminé',
                        ProjectStatus::Archived->value => 'Archivé',
                    ])
                    ->default(ProjectStatus::Active->value)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ColorColumn::make('color')
                    ->label('Marque')
                    ->copyable(),
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
                    ->label('Budget')
                    ->suffix(' h')
                    ->sortable()
                    ->placeholder('Illimité'),
                Tables\Columns\TextColumn::make('consumed')
                    ->label('Consommé')
                    ->getStateUsing(fn (Project $record): string => $record->consumedHours().' h'),
                Tables\Columns\TextColumn::make('burn_rate')
                    ->label('Burn Rate')
                    ->getStateUsing(fn (Project $record): ?string => $record->burnRate() !== null ? $record->burnRate().' %' : 'N/A')
                    ->badge()
                    ->color(fn (Project $record): string => match (true) {
                        $record->burnRate() === null => 'gray',
                        $record->burnRate() > 100 => 'danger',
                        $record->burnRate() >= 80 => 'warning',
                        default => 'success',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (Project $record): string => match ($record->status) {
                        ProjectStatus::Active => 'success',
                        ProjectStatus::Completed => 'info',
                        ProjectStatus::Archived => 'gray',
                    })
                    ->formatStateUsing(fn (ProjectStatus $state): string => match ($state) {
                        ProjectStatus::Active => 'Actif',
                        ProjectStatus::Completed => 'Terminé',
                        ProjectStatus::Archived => 'Archivé',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        ProjectStatus::Active->value => 'Actif',
                        ProjectStatus::Completed->value => 'Terminé',
                        ProjectStatus::Archived->value => 'Archivé',
                    ]),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProjects::route('/'),
        ];
    }
}
