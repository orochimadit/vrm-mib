<?php

namespace App\Filament\Resources\Companies\Tables;

use App\Filament\Resources\Vessels\VesselResource;
use App\Models\Company;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CompaniesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No')
                    ->rowIndex(),
                TextColumn::make('name')
                    ->label('Company Name')
                    ->weight('semibold')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('contact_info')
                    ->label('Contact Info')
                    ->state(fn (Company $record) => $record->email ?: ($record->phone ?: '-'))
                    ->searchable(['email', 'phone']),
                TextColumn::make('pic')
                    ->label('PIC')
                    ->searchable()
                    ->placeholder('-'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('vessel')
                    ->label('Vessel')
                    ->color('info')
                    ->button()
                    ->size('sm')
                    ->url(fn (Company $record): string => VesselResource::getUrl('index', [
                        'tableFilters' => [
                            'owner_company_id' => ['value' => $record->id],
                        ],
                    ])),
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

