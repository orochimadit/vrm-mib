<?php

namespace App\Filament\Resources\Surveys\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SurveysTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vessel.name')
                    ->label('Kapal')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('vessel.imo_number')
                    ->label('IMO')
                    ->badge()
                    ->color('gray')
                    ->searchable(),
                TextColumn::make('condition_type')
                    ->label('Tipe Survei')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Completed' => 'success',
                        'Outstanding' => 'warning',
                        'Cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('form_status')
                    ->label('Form')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'complete' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('surveyor_name')
                    ->label('Surveyor')
                    ->searchable(),
                TextColumn::make('date_of_survey')
                    ->label('Tgl Survei')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('due_date')
                    ->label('Batas Waktu')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('location')
                    ->label('Lokasi')
                    ->searchable(),
                TextColumn::make('defects_count')
                    ->counts('defects')
                    ->label('Temuan Defect')
                    ->badge()
                    ->color('danger'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
