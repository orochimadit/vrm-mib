<?php

namespace App\Filament\Resources\Surveys\RelationManagers;

use App\Models\LogRectification;
use App\Models\SurveyDefect;
use App\Models\SurveyDefectFile;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
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
use Illuminate\Support\Str;

class DefectsRelationManager extends RelationManager
{
    protected static string $relationship = 'defects';

    protected static ?string $title = 'List of Deficiency (Temuan Kerusakan & Revisi Dokumen)';

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
                TextInput::make('rectification_time_days')
                    ->label('Batas Waktu Perbaikan (Hari)')
                    ->numeric()
                    ->default(14),
                Select::make('status')
                    ->label('Status Approval')
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
                    ->color('gray')
                    ->default('Umum'),
                TextColumn::make('explanation')
                    ->label('Temuan Kerusakan')
                    ->limit(40)
                    ->wrap()
                    ->searchable(),
                TextColumn::make('recommendation_rectification')
                    ->label('Rekomendasi Surveyor')
                    ->limit(35)
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
                TextColumn::make('files_count')
                    ->label('Dokumen Bukti')
                    ->counts('files')
                    ->badge()
                    ->color('info'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('+ Tambah Defisiensi Manual')
                    ->visible(fn () => in_array(Auth::user()?->role, ['admin_mcm', 'surveyor'])),
            ])
            ->recordActions([
                // 1. TOMBOL LOG DOKUMEN / RIWAYAT FILE (termasuk yang telah dihapus/diarsip)
                Action::make('log_files')
                    ->label('Log Dokumen')
                    ->icon('heroicon-o-document-magnifying-glass')
                    ->color('gray')
                    ->button()
                    ->size('xs')
                    ->modalHeading('Riwayat Dokumen Bukti Perbaikan')
                    ->modalDescription('Daftar seluruh berkas bukti fisik (PDF/Foto) dan link video perbaikan, termasuk riwayat dokumen yang sudah diganti atau diarsipkan.')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(fn (SurveyDefect $record) => view('filament.modals.defect-log-files', ['record' => $record])),

                // 2. TOMBOL LOG CATATAN / REMARK RECTIFICATION
                Action::make('log_remarks')
                    ->label('Log Catatan')
                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                    ->color('info')
                    ->button()
                    ->size('xs')
                    ->modalHeading('Log Riwayat Catatan & Status Perbaikan')
                    ->modalDescription('Histori kronologis rekaman perubahan catatan tanggapan dari pemilik kapal dan surveyor/admin.')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(fn (SurveyDefect $record) => view('filament.modals.defect-log-remarks', ['record' => $record])),

                // 3. TOMBOL UPLOAD DOKUMEN BUKTI BARU (Upload Rectification File)
                Action::make('upload_file')
                    ->label('Upload Bukti')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('warning')
                    ->button()
                    ->size('xs')
                    ->form([
                        TextInput::make('title')
                            ->label('Judul / Deskripsi Dokumen')
                            ->required()
                            ->placeholder('Contoh: Foto Perbaikan Plat Lambung Kanan'),
                        Select::make('type')
                            ->label('Tipe Dokumen')
                            ->options([
                                'file' => 'Berkas Fisik (PDF / Gambar JPG / PNG)',
                                'link' => 'Tautan Video / Cloud Storage (Link URL)',
                            ])
                            ->default('file')
                            ->live(),
                        FileUpload::make('uploaded_file')
                            ->label('Unggah Berkas Bukti (PDF / Image)')
                            ->disk('public')
                            ->directory('attachment')
                            ->visible(fn ($get) => $get('type') === 'file')
                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(51200),
                        TextInput::make('video_link')
                            ->label('Tautan Video / Dokumen (URL)')
                            ->url()
                            ->placeholder('https://...')
                            ->visible(fn ($get) => $get('type') === 'link'),
                    ])
                    ->action(function (SurveyDefect $record, array $data): void {
                        $fileType = $data['type'] ?? 'file';
                        $fileName = $data['uploaded_file'] ?? null;
                        $link = $data['video_link'] ?? null;

                        SurveyDefectFile::create([
                            'id_deffect' => $record->id,
                            'title' => $data['title'],
                            'type' => $fileType,
                            'file' => $fileName,
                            'link' => $link,
                        ]);

                        // Catat ke log_rectification
                        LogRectification::create([
                            'id_deffect' => $record->id,
                            'category' => 'rectification_owner',
                            'value' => "Upload dokumen: {$data['title']}",
                            'datetime' => now(),
                            'id_admin' => Auth::id(),
                        ]);

                        Notification::make()
                            ->title('Dokumen Bukti Berhasil Diunggah')
                            ->success()
                            ->send();
                    }),

                // 4. TOMBOL RESPON PERBAIKAN DARI OWNER (Rectification)
                Action::make('owner_rectify')
                    ->label('Isi Revisi')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->button()
                    ->size('xs')
                    ->form([
                        Textarea::make('rectification_owner')
                            ->label('Catatan Perbaikan dari Pemilik Kapal')
                            ->required()
                            ->rows(4)
                            ->default(fn (SurveyDefect $record) => $record->rectification_owner),
                    ])
                    ->action(function (SurveyDefect $record, array $data): void {
                        $record->update([
                            'rectification_owner' => $data['rectification_owner'],
                        ]);

                        LogRectification::create([
                            'id_deffect' => $record->id,
                            'category' => 'rectification_owner',
                            'value' => $data['rectification_owner'],
                            'datetime' => now(),
                            'id_admin' => Auth::id(),
                        ]);

                        Notification::make()
                            ->title('Catatan Perbaikan Berhasil Disimpan')
                            ->success()
                            ->send();
                    })
                    ->visible(fn () => in_array(Auth::user()?->role, ['admin_mcm', 'shipowner'])),

                // 5. TOMBOL APPROVE DARI SURVEYOR / ADMIN
                Action::make('approve_defect')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->button()
                    ->size('xs')
                    ->requiresConfirmation()
                    ->action(function (SurveyDefect $record): void {
                        $record->update(['status' => 'Approve']);

                        LogRectification::create([
                            'id_deffect' => $record->id,
                            'category' => 'status_remark',
                            'value' => 'Approve - Perbaikan Disetujui',
                            'datetime' => now(),
                            'id_admin' => Auth::id(),
                        ]);

                        Notification::make()
                            ->title('Defisiensi Berhasil Disetujui (Approved)')
                            ->success()
                            ->send();
                    })
                    ->visible(fn () => in_array(Auth::user()?->role, ['admin_mcm', 'surveyor'])),

                // 6. TOMBOL DECLINE DARI SURVEYOR / ADMIN
                Action::make('decline_defect')
                    ->label('Decline')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->button()
                    ->size('xs')
                    ->form([
                        Textarea::make('decline_reason')
                            ->label('Alasan Penolakan / Catatan Perbaikan Lanjutan')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (SurveyDefect $record, array $data): void {
                        $record->update(['status' => 'Decline']);

                        LogRectification::create([
                            'id_deffect' => $record->id,
                            'category' => 'status_remark',
                            'value' => "Decline - {$data['decline_reason']}",
                            'datetime' => now(),
                            'id_admin' => Auth::id(),
                        ]);

                        Notification::make()
                            ->title('Defisiensi Ditolak (Decline) dan Catatan Tersimpan')
                            ->warning()
                            ->send();
                    })
                    ->visible(fn () => in_array(Auth::user()?->role, ['admin_mcm', 'surveyor'])),

                EditAction::make()
                    ->label('Edit')
                    ->color('gray')
                    ->button()
                    ->size('xs'),
                DeleteAction::make()
                    ->label('Hapus')
                    ->size('xs'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
