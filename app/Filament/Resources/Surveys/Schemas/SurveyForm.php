<?php

namespace App\Filament\Resources\Surveys\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SurveyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Survei & Kapal')
                    ->description('Detail armada kapal dan status penugasan inspeksi maritim')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('vessel_id')
                                ->label('Pilih Armada Kapal')
                                ->relationship('vessel', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('condition_type')
                                ->label('Tipe Survei')
                                ->options([
                                    'Survey' => 'Survey (Inspeksi Fisik)',
                                    'Self Assessment' => 'Self Assessment (Mandiri)',
                                    'Photo Album' => 'Photo Album (Dokumentasi Foto)',
                                    'Underwriting' => 'Underwriting (Review Asuransi)',
                                ])
                                ->default('Survey')
                                ->required(),
                            Select::make('status')
                                ->label('Status Survei')
                                ->options([
                                    'Outstanding' => 'Outstanding (Berjalan)',
                                    'Completed' => 'Completed (Selesai)',
                                    'Cancelled' => 'Cancelled (Dibatalkan)',
                                ])
                                ->default('Outstanding')
                                ->required(),
                        ]),
                        Grid::make(3)->schema([
                            Select::make('surveyor_id')
                                ->label('Marine Surveyor')
                                ->relationship('surveyor', 'name')
                                ->searchable()
                                ->preload(),
                            Select::make('broker_id')
                                ->label('Broker Mitra')
                                ->relationship('broker', 'name')
                                ->searchable()
                                ->preload(),
                            Select::make('insurance_company_id')
                                ->label('Perusahaan Asuransi')
                                ->relationship('insuranceCompany', 'name')
                                ->searchable()
                                ->preload(),
                        ]),
                        Grid::make(3)->schema([
                            DatePicker::make('date_of_survey')
                                ->label('Tanggal Pelaksanaan Survei'),
                            DatePicker::make('due_date')
                                ->label('Batas Waktu (Due Date)'),
                            TextInput::make('location')
                                ->label('Lokasi / Pelabuhan Inspeksi')
                                ->placeholder('Contoh: Pelabuhan Tanjung Priok'),
                        ]),
                        Grid::make(2)->schema([
                            DatePicker::make('policy_period_start')
                                ->label('Policy Period Start (Mulai Polis)'),
                            DatePicker::make('policy_period_end')
                                ->label('Policy Period End (Akhir Polis)'),
                        ]),
                    ]),

                Section::make('📁 Upload Dokumen Sertifikat & Foto Bukti Survei')
                    ->description('Area upload bukti fisik kondisi kapal, scan sertifikat kelas (BKI/IACS), dan dokumen perizinan')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->schema([
                        FileUpload::make('attachments')
                            ->label('File Lampiran Dokumen & Foto Bukti')
                            ->multiple()
                            ->directory('surveys/attachments')
                            ->openable()
                            ->downloadable()
                            ->reorderable()
                            ->columnSpanFull()
                            ->helperText('Tarik dan lepaskan (Drag & drop) file di sini. Mendukung banyak file sekaligus: PDF Sertifikat Kelas BKI, Dokumen Perizinan, serta foto bukti fisik lambung/mesin (JPG/PNG/WEBP).'),
                    ]),

                Section::make('Catatan & Resume Surveyor')
                    ->description('Komentar teknis, kekhawatiran (*concern*), dan kesimpulan inspeksi')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->collapsed()
                    ->schema([
                        Textarea::make('concern_from_surveyor')
                            ->label('Catatan Kritis dari Surveyor (Concern)')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('resume_from_surveyor')
                            ->label('Resume Temuan Surveyor')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('summary')
                            ->label('Ringkasan Eksekutif (Summary)')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
