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
                    ->label('Nama Kapal')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('imo_number')
                    ->label('No. IMO')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type_name_legacy')
                    ->label('Tipe Kapal')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('flag')
                    ->label('Bendera')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ownerCompany.name')
                    ->label('Pemilik (Owner)')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('year_built')
                    ->label('Tahun')
                    ->sortable(),
                TextColumn::make('grt')
                    ->label('GRT')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('classification_society')
                    ->label('Klasifikasi')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tsi')
                    ->label('TSI')
                    ->money('USD')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
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
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
