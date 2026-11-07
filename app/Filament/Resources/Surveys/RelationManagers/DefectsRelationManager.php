<?php

namespace App\Filament\Resources\Surveys\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class DefectsRelationManager extends RelationManager
{
    protected static string $relationship = 'defects';

    protected static ?string $title = 'Temuan Kerusakan & Revisi Perbaikan (Defects)';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('item_category')
                    ->label('Kategori Bagian Kapal (Hull / Mesin / Safety)')
                    ->maxLength(100),
                Textarea::make('explanation')
                    ->label('Temuan Kerusakan / Cacat Teknis')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                Textarea::make('recommendation_rectification')
                    ->label('Rekomendasi Perbaikan dari Surveyor')
                    ->rows(3)
                    ->columnSpanFull(),
                Textarea::make('rectification_owner')
                    ->label('Catatan Perbaikan dari Pemilik Kapal (Revisi)')
                    ->rows(3)
                    ->columnSpanFull(),
                Select::make('rectification_status')
                    ->label('Status Pengerjaan Perbaikan')
                    ->options([
                        'Pending' => 'Pending (Belum Diperbaiki)',
                        'In Progress' => 'In Progress (Sedang Dikerjakan)',
                        'Completed' => 'Completed (Selesai Diperbaiki)',
                    ])
                    ->default('Pending'),
                Select::make('status')
                    ->label('Status Approval Asuransi')
                    ->options([
                        'Outstanding' => 'Outstanding (Menunggu Review)',
                        'Approve' => 'Approve (Disetujui)',
                        'Decline' => 'Decline (Ditolak)',
                    ])
                    ->default('Outstanding'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('explanation')
            ->columns([
                TextColumn::make('item_category')
                    ->label('Kategori')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('explanation')
                    ->label('Temuan Kerusakan')
                    ->limit(45)
                    ->wrap()
                    ->searchable(),
                TextColumn::make('recommendation_rectification')
                    ->label('Rekomendasi')
                    ->limit(40)
                    ->wrap()
                    ->searchable(),
                TextColumn::make('rectification_owner')
                    ->label('Revisi Owner')
                    ->limit(35)
                    ->wrap()
                    ->searchable()
                    ->formatStateUsing(fn (?string $state): string => filled($state) ? Str::limit($state, 30) : '— Belum Ada —'),
                TextColumn::make('status')
                    ->label('Approval')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Approve' => 'success',
                        'Outstanding' => 'warning',
                        'Decline' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('rectification_status')
                    ->label('Perbaikan')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Completed' => 'success',
                        'In Progress' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()->label('+ Tambah Defect'),
            ])
            ->recordActions([
                EditAction::make()->label('Revisi'),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
