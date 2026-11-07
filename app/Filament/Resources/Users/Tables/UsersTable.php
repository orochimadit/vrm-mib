<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('#')
                    ->rowIndex()
                    ->width('60px'),
                TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->weight('medium')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('username')
                    ->label('Username')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('role')
                    ->label('Level')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'admin_mcm' => 'Admin MCM',
                        'insurance' => 'Insurance',
                        'surveyor' => 'Surveyor',
                        'shipowner' => 'Ship Owner',
                        'broker' => 'Broker',
                        default => ucfirst($state ?? '-'),
                    })
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('name')
                    ->form([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->placeholder('cari Nama...'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['name'],
                            fn (Builder $query, $name): Builder => $query->where('name', 'ilike', "%{$name}%"),
                        );
                    }),
                Filter::make('username')
                    ->form([
                        TextInput::make('username')
                            ->label('Username')
                            ->placeholder('cari username...'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['username'],
                            fn (Builder $query, $username): Builder => $query->where('username', 'ilike', "%{$username}%"),
                        );
                    }),
                SelectFilter::make('role')
                    ->label('Level')
                    ->options([
                        'admin_mcm' => 'Admin MCM',
                        'insurance' => 'Insurance',
                        'surveyor' => 'Surveyor',
                        'shipowner' => 'Ship Owner',
                        'broker' => 'Broker',
                    ])
                    ->placeholder('pilih...'),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->label('Edit Pengguna')
                        ->color('warning'),
                    DeleteAction::make()
                        ->label('Hapus Pengguna')
                        ->color('danger'),
                ])
                ->icon('heroicon-o-cog-6-tooth')
                ->label('Aksi')
                ->button()
                ->color('gray')
                ->size('sm'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
