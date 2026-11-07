<?php

namespace App\Filament\Resources\Surveys\Tables;

use App\Filament\Resources\Surveys\SurveyResource;
use App\Models\Survey;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
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
                    ->formatStateUsing(fn (Survey $record): string => 
                        $record->vessel?->name . ($record->vessel?->ownerCompany?->name ? " - " . $record->vessel->ownerCompany->name : '')
                    )
                    ->searchable()
                    ->sortable(),

                TextColumn::make('insuranceCompany.name')
                    ->label('Insurance')
                    ->formatStateUsing(fn (Survey $record): string => $record->insuranceCompany?->name ?? '—')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('surveyor_name')
                    ->label('Surveyor Name')
                    ->default(fn (Survey $record): string => $record->surveyor?->name ?? '—')
                    ->searchable(),

                TextColumn::make('location')
                    ->label('Location of Survey')
                    ->default('—')
                    ->searchable(),

                TextColumn::make('policy_period_start')
                    ->label('Policy Period Start')
                    ->date('Y-m-d')
                    ->sortable(),

                TextColumn::make('policy_period_end')
                    ->label('Policy Period End')
                    ->date('Y-m-d')
                    ->sortable(),

                TextColumn::make('due_date')
                    ->label('Due Date')
                    ->date('Y-m-d')
                    ->sortable(),

                TextColumn::make('condition_type')
                    ->label('Risk Treatment')
                    ->badge()
                    ->color('info')
                    ->default('Self Assessment')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Completed' => 'success',
                        'Outstanding' => 'danger',
                        'Terminated' => 'gray',
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
                // 1. CONDITION (Sesuai Legacy btn-info)
                Action::make('condition')
                    ->label('Condition')
                    ->color('info')
                    ->button()
                    ->size('xs')
                    ->modalHeading('Form Condition Survey')
                    ->form([
                        TextInput::make('surveyor_name')
                            ->label('Surveyor Name')
                            ->required(),
                        TextInput::make('location')
                            ->label('Location of Survey'),
                        DatePicker::make('date_of_survey')
                            ->label('Date of Survey'),
                        DatePicker::make('policy_period_start')
                            ->label('Policy Period Start'),
                        DatePicker::make('policy_period_end')
                            ->label('Policy Period End'),
                        DatePicker::make('due_date')
                            ->label('Due Date'),
                    ])
                    ->fillForm(fn (Survey $record): array => [
                        'surveyor_name' => $record->surveyor_name,
                        'location' => $record->location,
                        'date_of_survey' => $record->date_of_survey,
                        'policy_period_start' => $record->policy_period_start,
                        'policy_period_end' => $record->policy_period_end,
                        'due_date' => $record->due_date,
                    ])
                    ->action(function (Survey $record, array $data): void {
                        $record->update($data);
                        Notification::make()->title('Condition Survey berhasil diperbarui')->success()->send();
                    }),

                // 2. SURVEY (Sesuai Legacy btn-primary -> Checklist Penilaian Lapangan)
                Action::make('survey')
                    ->label('Survey')
                    ->color('primary')
                    ->button()
                    ->size('xs')
                    ->url(fn (Survey $record): string => SurveyResource::getUrl('checklist', ['record' => $record])),

                // 3. RESULT (Sesuai Legacy btn-success -> Halaman Evaluasi & Laporan Resmi)
                Action::make('result')
                    ->label('Result')
                    ->color('success')
                    ->button()
                    ->size('xs')
                    ->url(fn (Survey $record): string => route('surveys.report', $record))
                    ->openUrlInNewTab(true),

                // 4. LIST OF DEFICIENCY (Sesuai Legacy btn-danger)
                Action::make('deficiency')
                    ->label('List Of Deficiency')
                    ->color('danger')
                    ->button()
                    ->size('xs')
                    ->badge(fn (Survey $record) => $record->defects()->where('status', 'Outstanding')->count() ?: null)
                    ->url(fn (Survey $record): string => SurveyResource::getUrl('deficiency', ['record' => $record])),

                // 5. EDIT (Sesuai Legacy btn-warning)
                EditAction::make()
                    ->label('Edit')
                    ->color('warning')
                    ->button()
                    ->size('xs'),

                // 6. DELETE (Sesuai Legacy btn-danger)
                DeleteAction::make()
                    ->label('Delete')
                    ->color('danger')
                    ->button()
                    ->size('xs'),

                // 7. TERMINATED / UNTERMINATED (Sesuai Legacy btn-dark)
                Action::make('toggle_terminated')
                    ->label(fn (Survey $record): string => $record->status === 'Terminated' ? 'Unterminated' : 'Terminated')
                    ->color('gray')
                    ->button()
                    ->size('xs')
                    ->requiresConfirmation()
                    ->modalHeading(fn (Survey $record): string => $record->status === 'Terminated' ? 'Buka Kembali Survei (Unterminated)' : 'Tutup / Batalkan Survei (Terminated)')
                    ->action(function (Survey $record): void {
                        $newStatus = $record->status === 'Terminated' ? 'Outstanding' : 'Terminated';
                        $record->update(['status' => $newStatus]);
                        Notification::make()->title("Status survei diubah menjadi {$newStatus}")->success()->send();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
