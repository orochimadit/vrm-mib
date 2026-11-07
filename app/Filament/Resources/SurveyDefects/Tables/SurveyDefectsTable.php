<?php

namespace App\Filament\Resources\SurveyDefects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class SurveyDefectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('survey.vessel.name')
                    ->label('Armada Kapal')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('explanation')
                    ->label('Keterangan Kerusakan / Defect')
                    ->limit(50)
                    ->wrap()
                    ->searchable(),
                TextColumn::make('recommendation_rectification')
                    ->label('Rekomendasi Perbaikan')
                    ->limit(50)
                    ->wrap()
                    ->searchable(),
                TextColumn::make('rectification_owner')
                    ->label('Tanggapan Perbaikan Owner')
                    ->limit(40)
                    ->wrap()
                    ->searchable()
                    ->formatStateUsing(fn (?string $state): string => filled($state) ? Str::limit($state, 35) : '— Belum Ada Revisi —'),
                TextColumn::make('status')
                    ->label('Status Approval')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Approve' => 'success',
                        'Outstanding' => 'warning',
                        'Decline' => 'danger',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rectification_status')
                    ->label('Status Perbaikan')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Completed' => 'success',
                        'In Progress' => 'warning',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rectification_time_days')
                    ->label('Tenggat (Hari)')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Tgl Ditemukan')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status Approval')
                    ->options([
                        'Outstanding' => 'Outstanding (Belum Direview)',
                        'Approve' => 'Approve (Disetujui)',
                        'Decline' => 'Decline (Ditolak)',
                    ]),
                SelectFilter::make('rectification_status')
                    ->label('Filter Status Perbaikan')
                    ->options([
                        'Pending' => 'Pending (Belum Diperbaiki)',
                        'In Progress' => 'In Progress (Sedang Diperbaiki)',
                        'Completed' => 'Completed (Selesai Diperbaiki)',
                    ]),
            ])
            ->recordActions([
                EditAction::make()->label('Revisi / Edit'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
