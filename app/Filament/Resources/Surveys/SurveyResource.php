<?php

namespace App\Filament\Resources\Surveys;

use App\Filament\Resources\Surveys\Pages\CreateSurvey;
use App\Filament\Resources\Surveys\Pages\EditSurvey;
use App\Filament\Resources\Surveys\Pages\ListSurveys;
use App\Filament\Resources\Surveys\Pages\ManageChecklist;
use App\Filament\Resources\Surveys\Pages\ManageDeficiencies;
use App\Filament\Resources\Surveys\Schemas\SurveyForm;
use App\Filament\Resources\Surveys\Tables\SurveysTable;
use App\Filament\Resources\Surveys\RelationManagers\DefectsRelationManager;
use App\Filament\Resources\Surveys\RelationManagers\SurveyResponsesRelationManager;
use App\Models\Survey;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Vessel Risk Management';

    protected static ?string $modelLabel = 'Survei Risiko Kapal';

    protected static ?string $pluralModelLabel = 'Vessel Risk Management';

    protected static ?int $navigationSort = 6;

    public static function getNavigationUrl(): string
    {
        return route('surveys.legacy.hub');
    }

    public static function form(Schema $schema): Schema
    {
        return SurveyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SurveysTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            SurveyResponsesRelationManager::class,
            DefectsRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if (! $user) {
            return $query;
        }

        // 1. ADMIN MCM (Superadmin): Melihat semua data survei
        if ($user->role === 'admin_mcm') {
            return $query;
        }

        // 2. INSURANCE (Underwriter): Hanya melihat survei asuransi perusahaannya
        if ($user->role === 'insurance' && $user->reference_id) {
            return $query->where('insurance_company_id', $user->reference_id);
        }

        // 3. SURVEYOR: Hanya melihat survei yang ditugaskan ke lembaga survey-nya
        if ($user->role === 'surveyor' && $user->reference_id) {
            return $query->where('surveyor_id', $user->reference_id);
        }

        // 4. SHIPOWNER: Hanya melihat survei kapal miliknya
        if ($user->role === 'shipowner' && $user->reference_id) {
            return $query->whereHas('vessel', function (Builder $q) use ($user) {
                $q->where('owner_company_id', $user->reference_id);
            });
        }

        // 5. BROKER: Hanya melihat survei melalui broker perusahaannya
        if ($user->role === 'broker' && $user->reference_id) {
            return $query->where('broker_id', $user->reference_id);
        }

        return $query;
    }

    public static function canCreate(): bool
    {
        $role = Auth::user()?->role;
        return in_array($role, ['admin_mcm', 'surveyor']);
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::user()?->role === 'admin_mcm';
    }

    public static function canDeleteAny(): bool
    {
        return Auth::user()?->role === 'admin_mcm';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSurveys::route('/'),
            'create' => CreateSurvey::route('/create'),
            'edit' => EditSurvey::route('/{record}/edit'),
            'checklist' => ManageChecklist::route('/{record}/checklist'),
            'deficiency' => ManageDeficiencies::route('/{record}/deficiency'),
        ];
    }
}
