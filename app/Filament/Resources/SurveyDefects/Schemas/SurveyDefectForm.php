<?php

namespace App\Filament\Resources\SurveyDefects\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SurveyDefectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Temuan Kerusakan (Defect Finding)')
                    ->description('Laporan temuan kerusakan fisik kapal oleh Marine Surveyor')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->schema([
                        Select::make('survey_id')
                            ->label('Pilih Tiket Survei & Kapal')
                            ->relationship('survey', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "Survei #{$record->id} — " . ($record->vessel?->name ?? 'Kapal') . " (" . ($record->date_of_survey?->format('d M Y') ?? 'Tgl -') . ")")
                            ->searchable()
                            ->preload()
                            ->required(),
                        Grid::make(2)->schema([
                            TextInput::make('item_category')
                                ->label('Kategori Bagian Kapal')
                                ->placeholder('Contoh: Hull Plating, Main Engine, Piping'),
                            TextInput::make('rectification_time_days')
                                ->label('Tenggat Waktu Perbaikan (Hari)')
                                ->numeric()
                                ->placeholder('Contoh: 14'),
                        ]),
                        Textarea::make('explanation')
                            ->label('Deskripsi Temuan Kerusakan / Cacat Teknis')
                            ->rows(3)
                            ->columnSpanFull()
                            ->required(),
                        Textarea::make('recommendation_rectification')
                            ->label('Rekomendasi Tindakan Perbaikan dari Surveyor')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('🔧 Revisi & Tanggapan Perbaikan (Owner Rectification)')
                    ->description('Diisi oleh Pemilik Kapal (Shipowner) untuk melaporkan perbaikan yang telah dilakukan')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->schema([
                        Textarea::make('rectification_owner')
                            ->label('Catatan Hasil Perbaikan dari Pemilik Kapal')
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder('Jelaskan tindakan perbaikan yang telah diselesaikan...'),
                        Select::make('rectification_status')
                            ->label('Status Pengerjaan Perbaikan')
                            ->options([
                                'Pending' => 'Pending (Belum Diperbaiki)',
                                'In Progress' => 'In Progress (Sedang Dikerjakan)',
                                'Completed' => 'Completed (Selesai Dikerjakan)',
                            ])
                            ->default('Pending')
                            ->required(),
                        FileUpload::make('attachments')
                            ->label('Upload Foto Bukti Perbaikan')
                            ->multiple()
                            ->directory('defects/rectifications')
                            ->openable()
                            ->downloadable()
                            ->columnSpanFull()
                            ->helperText('Unggah foto bukti fisik hasil pengelasan, penggantian part, atau perbaikan di dock.'),
                    ]),

                Section::make('🛡️ Penilaian & Persetujuan (Underwriter / Admin Approval)')
                    ->description('Keputusan persetujuan oleh Underwriter Asuransi atau Admin MCM')
                    ->icon('heroicon-o-shield-check')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('status')
                                ->label('Keputusan Status Approval')
                                ->options([
                                    'Outstanding' => 'Outstanding (Menunggu Review)',
                                    'Approve' => 'Approve (Disetujui / Sesuai Standar)',
                                    'Decline' => 'Decline (Ditolak / Belum Memenuhi Syarat)',
                                ])
                                ->default('Outstanding')
                                ->required(),
                            Textarea::make('remark_defect')
                                ->label('Catatan Review dari Asuransi / Admin')
                                ->rows(2),
                        ]),
                    ]),
            ]);
    }
}
