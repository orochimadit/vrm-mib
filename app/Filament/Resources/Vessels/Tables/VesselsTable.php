<?php

namespace App\Filament\Resources\Vessels\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VesselsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Vessel Name')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type_name_legacy')
                    ->label('Type')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('grt')
                    ->label('GRT')
                    ->numeric()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('tsi')
                    ->label('TSI')
                    ->numeric(2)
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('ownerCompany.name')
                    ->label('Company Name')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('owner_company_id')
                    ->label('Filter Perusahaan Pemilik')
                    ->relationship('ownerCompany', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Edit')
                    ->color('warning')
                    ->button()
                    ->size('sm'),
                DeleteAction::make()
                    ->label('Delete')
                    ->color('danger')
                    ->button()
                    ->size('sm'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

