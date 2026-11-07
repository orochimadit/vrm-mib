<?php

namespace App\Filament\Resources\Surveys\Tables;

use App\Filament\Resources\Surveys\SurveyResource;
use App\Models\Survey;
use Filament\Actions\Action;
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
                    ->label('Vessel Name')
                    ->weight('bold')
                    ->description(fn (Survey $record): string => $record->vessel?->ownerCompany?->name ? "Owner: {$record->vessel->ownerCompany->name}" : '')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('vessel.imo_number')
                    ->label('IMO')
                    ->badge()
                    ->color('gray')
                    ->searchable(),
                TextColumn::make('insuranceCompany.name')
                    ->label('Insurance')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('surveyor_name')
                    ->label('Surveyor Name')
                    ->default(fn (Survey $record): string => $record->surveyor?->name ?? '—')
                    ->searchable(),
                TextColumn::make('location')
                    ->label('Location of Survey')
                    ->searchable(),
                TextColumn::make('due_date')
                    ->label('Due Date')
                    ->date('d-M-Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Completed' => 'success',
                        'Outstanding' => 'danger',
                        'Cancelled' => 'dark',
                        default => 'secondary',
                    })
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('survey')
                    ->label('Survey')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('info')
                    ->button()
                    ->size('sm')
                    ->url(fn (Survey $record): string => SurveyResource::getUrl('checklist', ['record' => $record])),
                Action::make('deficiency')
                    ->label('List Of Deficiency')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('danger')
                    ->button()
                    ->size('sm')
                    ->badge(fn (Survey $record) => $record->defects()->count() ?: null)
                    ->url(fn (Survey $record): string => SurveyResource::getUrl('deficiency', ['record' => $record])),
                EditAction::make()
                    ->label('Edit')
                    ->color('warning')
                    ->button()
                    ->size('sm'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
