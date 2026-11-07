<?php

namespace App\Filament\Resources\Surveys\Pages;

use App\Filament\Resources\Surveys\SurveyResource;
use App\Models\LogRectification;
use App\Models\SurveyDefect;
use App\Models\SurveyDefectFile;
use BackedEnum;
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
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ManageDeficiencies extends ManageRelatedRecords
{
    protected static string $resource = SurveyResource::class;

    protected static string $relationship = 'defects';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    public function getTitle(): string|Htmlable
    {
        $vesselName = $this->getRecord()->vessel?->name ?? 'Survey #' . $this->getRecord()->id;
        return "List of Deficiency — {$vesselName}";
    }

    public function getBreadcrumb(): string
    {
        return 'List of Deficiency';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('← Kembali ke Daftar Survei')
                ->color('gray')
                ->button()
                ->url(SurveyResource::getUrl('index')),

            Action::make('complete_survey')
                ->label('✓ Selesaikan Survei (Complete)')
                ->color('success')
                ->button()
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Penyelesaian Survei')
                ->modalDescription('Apakah Anda yakin ingin menyelesaikan survei ini? Status survei akan diubah menjadi Completed.')
                ->visible(function () {
                    $openCount = $this->getRecord()->defects()->where('status', 'Outstanding')->count();
                    return $openCount === 0 && in_array(Auth::user()?->role, ['admin_mcm', 'surveyor']);
                })
                ->action(function (): void {
                    $this->getRecord()->update(['status' => 'Completed']);

                    Notification::make()
                        ->title('Survei Berhasil Diselesaikan (Completed)')
                        ->success()
                        ->send();
                }),

            CreateAction::make()
                ->label('+ Tambah Defisiensi Manual')
                ->visible(fn () => in_array(Auth::user()?->role, ['admin_mcm', 'surveyor'])),
        ];
    }

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
                    ->limit(45)
                    ->wrap()
                    ->searchable(),
                TextColumn::make('recommendation_rectification')
                    ->label('Rekomendasi Surveyor')
                    ->limit(40)
                    ->wrap()
                    ->searchable(),
                TextColumn::make('rectification_owner')
                    ->label('Revisi Owner')
                    ->limit(35)
                    ->wrap()
                    ->searchable()
                    ->formatStateUsing(fn (?string $state): string => filled($state) ? Str::limit($state, 30) : '— Belum Ada —'),
                TextColumn::make('rectification_time_days')
                    ->label('Target (Hari)')
                    ->badge()
                    ->color('secondary')
                    ->formatStateUsing(fn ($state) => $state ? "{$state} Hari" : '—'),
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
                    ->color('info')
                    ->formatStateUsing(fn (int $state): string => $state > 0 ? "{$state} Berkas" : '0 File'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                // 1. TOMBOL LOG DOKUMEN / RIWAYAT FILE (Termasuk Log Dokumen yang Dihapus/Diarsipkan oleh Shipowner)
                Action::make('log_files')
                    ->label('Log Dokumen')
                    ->icon('heroicon-o-document-magnifying-glass')
                    ->color(fn (SurveyDefect $record) => $record->logRectificationFiles()->exists() ? 'warning' : 'gray')
                    ->button()
                    ->size('xs')
                    ->badge(fn (SurveyDefect $record) => $record->logRectificationFiles()->count() ?: null)
                    ->badgeColor('warning')
                    ->tooltip('Lihat riwayat dokumen aktif dan rekaman berkas yang dihapus/diarsipkan oleh Shipowner')
                    ->modalHeading('Riwayat Dokumen Bukti Perbaikan & Log File Diarsipkan')
                    ->modalDescription('Daftar berkas bukti aktif serta log audit dokumen yang pernah diunggah lalu diganti/dihapus oleh Ship Owner saat revisi.')
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
