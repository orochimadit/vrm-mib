<?php

namespace App\Filament\Resources\Vessels\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VesselsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('ex_name')
                    ->searchable(),
                TextColumn::make('imo_number')
                    ->searchable(),
                TextColumn::make('flag')
                    ->searchable(),
                TextColumn::make('vesselType.name')
                    ->searchable(),
                TextColumn::make('type_name_legacy')
                    ->searchable(),
                TextColumn::make('ownerCompany.name')
                    ->searchable(),
                TextColumn::make('operatorCompany.name')
                    ->searchable(),
                TextColumn::make('classification_society')
                    ->searchable(),
                TextColumn::make('class_recommendation_issued')
                    ->searchable(),
                TextColumn::make('exemption_certificates_issued')
                    ->searchable(),
                TextColumn::make('year_built')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('place_built')
                    ->searchable(),
                TextColumn::make('grt')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dwt')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tsi')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dimensions')
                    ->searchable(),
                TextColumn::make('recent_port_of_call')
                    ->searchable(),
                TextColumn::make('trading_area')
                    ->searchable(),
                TextColumn::make('date_of_last_drydock')
                    ->date()
                    ->sortable(),
                TextColumn::make('date_of_next_drydock')
                    ->date()
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
