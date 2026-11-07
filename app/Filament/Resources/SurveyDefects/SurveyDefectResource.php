<?php

namespace App\Filament\Resources\SurveyDefects;

use App\Filament\Resources\SurveyDefects\Pages\CreateSurveyDefect;
use App\Filament\Resources\SurveyDefects\Pages\EditSurveyDefect;
use App\Filament\Resources\SurveyDefects\Pages\ListSurveyDefects;
use App\Filament\Resources\SurveyDefects\Schemas\SurveyDefectForm;
use App\Filament\Resources\SurveyDefects\Tables\SurveyDefectsTable;
use App\Models\SurveyDefect;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SurveyDefectResource extends Resource
{
    protected static ?string $model = SurveyDefect::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static ?string $navigationLabel = 'Temuan Kerusakan (Defects)';

    protected static ?string $modelLabel = 'Defect / Kerusakan';

    protected static ?string $pluralModelLabel = 'Temuan Kerusakan & Revisi';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return SurveyDefectForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SurveyDefectsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSurveyDefects::route('/'),
            'create' => CreateSurveyDefect::route('/create'),
            'edit' => EditSurveyDefect::route('/{record}/edit'),
        ];
    }
}
