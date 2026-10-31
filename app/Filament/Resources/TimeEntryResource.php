<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimeEntryResource\Pages;
use App\Models\TimeEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TimeEntryResource extends Resource
{
    protected static ?string $model = TimeEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Feuilles de temps';

    protected static ?string $modelLabel = 'Saisie de temps';

    protected static ?string $pluralModelLabel = 'Feuilles de temps';

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
                Forms\Components\Select::make('project_id')
                    ->label('Projet')
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Forms\Components\TextInput::make('description')
                    ->label('Description du travail')
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('started_at')
                    ->label('Début de session')
                    ->required(),
                Forms\Components\DateTimePicker::make('ended_at')
                    ->label('Fin de session')
                    ->nullable(),
                Forms\Components\Toggle::make('is_pomodoro')
                    ->label('Session Pomodoro')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Collaborateur')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                Tables\Columns\TextColumn::make('project.name')
                    ->label('Projet')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Sans projet'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Activité')
                    ->limit(35)
                    ->placeholder('Aucune description'),
                Tables\Columns\TextColumn::make('started_at')
                    ->label('Démarré le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Durée')
                    ->getStateUsing(function (TimeEntry $record): string {
                        $seconds = $record->currentDuration();
                        $hours = floor($seconds / 3600);
                        $minutes = floor(($seconds % 3600) / 60);

                        return sprintf('%dh %02dm', $hours, $minutes);
                    })
                    ->badge()
                    ->color(fn (TimeEntry $record): string => $record->currentDuration() > 28800 ? 'danger' : 'gray'),
                Tables\Columns\TextColumn::make('alert')
                    ->label('Alerte')
                    ->getStateUsing(fn (TimeEntry $record): ?string => $record->currentDuration() > 28800 ? 'Timer > 8h' : null)
                    ->badge()
                    ->color('danger')
                    ->placeholder('—'),
                Tables\Columns\IconColumn::make('is_pomodoro')
                    ->label('Pomodoro')
                    ->boolean()
                    ->trueIcon('heroicon-o-fire')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('warning')
                    ->falseColor('gray'),
            ])
            ->defaultSort('started_at', 'desc')
            ->filters([
                Tables\Filters\Filter::make('running')
                    ->label('Timers en cours')
                    ->query(fn ($query) => $query->whereNull('ended_at')),
                Tables\Filters\Filter::make('over_8_hours')
                    ->label('Sessions > 8 heures')
                    ->query(fn ($query) => $query->where('duration_seconds', '>', 28800)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTimeEntries::route('/'),
        ];
    }
}
