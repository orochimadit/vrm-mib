<?php

namespace App\Filament\Resources\Claims\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ClaimsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vessel.name')
                    ->label('Vessel Name')
                    ->weight('semibold')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('insuranceCompany.name')
                    ->label('Insurance Company')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('casualtyRef.casualty')
                    ->label('Casualty')
                    ->badge()
                    ->color('info')
                    ->placeholder('-'),
                TextColumn::make('date_of_loss')
                    ->label('Date of Loss')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('location')
                    ->label('Location')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('estimated_claim')
                    ->label('Estimated Claim')
                    ->numeric(2)
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'accepted' => 'success',
                        'ongoing' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'ongoing' => 'Ongoing',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                    ]),
                SelectFilter::make('portfolio_consultant')
                    ->options([
                        'consultant' => 'Consultant',
                        'portfolio' => 'Portofolio',
                        'all_portfolio' => 'All Portofolio',
                    ]),
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
