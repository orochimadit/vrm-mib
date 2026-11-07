<?php

namespace App\Filament\Resources\Surveys\RelationManagers;

use App\Models\SurveyResponse;
use App\Models\SurveyResponseAttachment;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class SurveyResponsesRelationManager extends RelationManager
{
    protected static string $relationship = 'responses';

    protected static ?string $title = 'Checklist Penilaian Lapangan & Upload Dokumen per Field';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('response')
                    ->label('Nilai Respon Lapangan')
                    ->options([
                        '1' => '1 - Poor (Buruk / Defisiensi)',
                        '2' => '2 - Fair (Cukup)',
                        '3' => '3 - Good / Satisfactory (Baik)',
                        '4' => '4 - Excellent (Sangat Baik)',
                        'N/A' => 'N/A (Tidak Berlaku)',
                    ])
                    ->required(),
                Textarea::make('remarks_from_surveyor')
                    ->label('Catatan Evaluasi / Remark dari Surveyor')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('response')
            ->columns([
                TextColumn::make('item.ref_no')
                    ->label('Ref')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('item.category')
                    ->label('Kategori')
                    ->badge()
                    ->color('info')
                    ->searchable(),
                TextColumn::make('item.item_name')
                    ->label('Pertanyaan / Item Pemeriksaan')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('response')
                    ->label('Respon')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        '3', '4', 'Good', 'Excellent', 'Satisfactory' => 'success',
                        '2', 'Fair' => 'warning',
                        '1', 'Poor' => 'danger',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        '1' => 'Poor',
                        '2' => 'Fair',
                        '3' => 'Satisfactory',
                        '4' => 'Excellent',
                        default => $state ?? '—',
                    }),
                TextColumn::make('remarks_from_surveyor')
                    ->label('Catatan Surveyor')
                    ->limit(35)
                    ->wrap()
                    ->searchable()
                    ->default('—'),
                TextColumn::make('attachments_count')
                    ->label('Lampiran Dokumen')
                    ->counts('attachments')
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'success' : 'danger')
                    ->formatStateUsing(fn (int $state): string => $state > 0 ? "{$state} Berkas" : '0 File'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                // 1. LIHAT DOKUMEN / FOTO LAMPIRAN FIELD INI
                Action::make('view_attachments')
                    ->label('Lihat Dokumen')
                    ->icon('heroicon-o-paper-clip')
                    ->color('info')
                    ->button()
                    ->size('xs')
                    ->modalHeading('Lampiran Dokumen & Foto Bukti Field Pemeriksaan')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(fn (SurveyResponse $record) => view('filament.modals.response-attachments', ['record' => $record])),

                // 2. UPLOAD DOKUMEN / SERTIFIKAT KHUSUS KE FIELD INI
                Action::make('upload_attachment')
                    ->label('Upload Dokumen')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('primary')
                    ->button()
                    ->size('xs')
                    ->form([
                        TextInput::make('title')
                            ->label('Judul / Nama Dokumen')
                            ->required()
                            ->placeholder('Contoh: Scan Sertifikat Safe Manning / Foto Lambung'),
                        Select::make('type')
                            ->label('Tipe Berkas')
                            ->options([
                                'file' => 'Berkas Fisik (PDF / Gambar JPG / PNG)',
                                'link' => 'Tautan Cloud / Video Link (URL)',
                            ])
                            ->default('file')
                            ->live(),
                        FileUpload::make('uploaded_file')
                            ->label('Unggah Berkas Dokumen')
                            ->disk('public')
                            ->directory('attachment')
                            ->visible(fn ($get) => $get('type') === 'file')
                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(51200),
                        TextInput::make('video_link')
                            ->label('Tautan Link (URL)')
                            ->url()
                            ->placeholder('https://...')
                            ->visible(fn ($get) => $get('type') === 'link'),
                    ])
                    ->action(function (SurveyResponse $record, array $data): void {
                        $fileType = $data['type'] ?? 'file';
                        $fileName = $data['uploaded_file'] ?? null;
                        $link = $data['video_link'] ?? null;

                        SurveyResponseAttachment::create([
                            'response_id' => $record->response_id,
                            'title' => $data['title'],
                            'type' => $fileType,
                            'file' => $fileName,
                            'link' => $link,
                        ]);

                        Notification::make()
                            ->title('Dokumen Berhasil Terlampir pada Field Ini')
                            ->success()
                            ->send();
                    })
                    ->visible(fn () => in_array(Auth::user()?->role, ['admin_mcm', 'surveyor', 'shipowner'])),

                EditAction::make()
                    ->label('Edit Respon')
                    ->color('warning')
                    ->button()
                    ->size('xs')
                    ->visible(fn () => in_array(Auth::user()?->role, ['admin_mcm', 'surveyor'])),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
