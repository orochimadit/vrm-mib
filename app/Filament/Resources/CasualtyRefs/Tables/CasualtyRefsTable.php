<?php

namespace App\Filament\Resources\CasualtyRefs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CasualtyRefsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->width('80px'),
                TextColumn::make('casualty')
                    ->label('Casualty')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),
            ])
            ->filters([
                //
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

