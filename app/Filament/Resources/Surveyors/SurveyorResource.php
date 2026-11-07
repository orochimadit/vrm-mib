<?php

namespace App\Filament\Resources\Surveyors;

use App\Filament\Resources\Surveyors\Pages\CreateSurveyor;
use App\Filament\Resources\Surveyors\Pages\EditSurveyor;
use App\Filament\Resources\Surveyors\Pages\ListSurveyors;
use App\Filament\Resources\Surveyors\Schemas\SurveyorForm;
use App\Filament\Resources\Surveyors\Tables\SurveyorsTable;
use App\Models\Surveyor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class SurveyorResource extends Resource
{
    protected static ?string $model = Surveyor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel = 'Surveyor Company';

    protected static ?string $modelLabel = 'Surveyor Company';

    protected static ?string $pluralModelLabel = 'Surveyor Company';

    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        return Auth::user()?->role === 'admin_mcm';
    }

    public static function form(Schema $schema): Schema
    {
        return SurveyorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SurveyorsTable::configure($table);
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
            'index' => ListSurveyors::route('/'),
            'create' => CreateSurveyor::route('/create'),
            'edit' => EditSurveyor::route('/{record}/edit'),
        ];
    }
}
