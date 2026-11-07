<?php

namespace App\Filament\Resources\InsuranceProducts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InsuranceProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('insuranceCompany.name')
                    ->label('Company')
                    ->weight('semibold')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Name')
                    ->weight('medium')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(40)
                    ->wrap()
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('insurance_company_id')
                    ->label('Filter Insurance Company')
                    ->relationship('insuranceCompany', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Edit')
                    ->color('info')
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
