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
                    ->searchable(),
                TextColumn::make('surveyor.name')
                    ->searchable(),
                TextColumn::make('broker.name')
                    ->searchable(),
                TextColumn::make('insuranceCompany.name')
                    ->searchable(),
                TextColumn::make('surveyor_name')
                    ->searchable(),
                TextColumn::make('condition_type')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('form_status')
                    ->searchable(),
                TextColumn::make('location')
                    ->searchable(),
                TextColumn::make('date_of_survey')
                    ->date()
                    ->sortable(),
                TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('policy_period_start')
                    ->date()
                    ->sortable(),
                TextColumn::make('policy_period_end')
                    ->date()
                    ->sortable(),
                TextColumn::make('risk_score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('risk_rating')
                    ->searchable(),
                TextColumn::make('created_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
