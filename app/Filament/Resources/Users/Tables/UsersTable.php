<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Pengguna')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('username')
                    ->label('Username')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role')
                    ->label('Role Akses')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'admin_mcm' => 'Admin MCM',
                        'insurance' => 'Insurance Partner',
                        'surveyor' => 'Surveyor',
                        'shipowner' => 'Ship Owner',
                        'broker' => 'Broker',
                        default => ucfirst($state ?? '-'),
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'admin_mcm' => 'danger',
                        'insurance' => 'warning',
                        'surveyor' => 'info',
                        'shipowner' => 'success',
                        'broker' => 'primary',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Tgl Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Filter Berdasarkan Role')
                    ->options([
                        'admin_mcm' => 'Admin MCM (Superadmin)',
                        'insurance' => 'Insurance Partner (Underwriter)',
                        'surveyor' => 'Marine Surveyor',
                        'shipowner' => 'Ship Owner (Pemilik Kapal)',
                        'broker' => 'Insurance Broker',
                    ]),
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
