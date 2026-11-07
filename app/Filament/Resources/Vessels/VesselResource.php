<?php

namespace App\Filament\Resources\Vessels;

use App\Filament\Resources\Vessels\Pages\CreateVessel;
use App\Filament\Resources\Vessels\Pages\EditVessel;
use App\Filament\Resources\Vessels\Pages\ListVessels;
use App\Filament\Resources\Vessels\Schemas\VesselForm;
use App\Filament\Resources\Vessels\Tables\VesselsTable;
use App\Models\Vessel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class VesselResource extends Resource
{
    protected static ?string $model = Vessel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static ?string $navigationLabel = 'Vessel & Owner Company';

    protected static ?string $modelLabel = 'Armada Kapal';

    protected static ?string $pluralModelLabel = 'Vessel & Owner Company';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return VesselForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VesselsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if (! $user) {
            return $query;
        }

        if ($user->role === 'admin_mcm') {
            return $query;
        }

        if ($user->role === 'shipowner' && $user->reference_id) {
            return $query->where('owner_company_id', $user->reference_id);
        }

        if ($user->role === 'surveyor' && $user->reference_id) {
            return $query->whereHas('surveys', fn ($q) => $q->where('surveyor_id', $user->reference_id));
        }

        if ($user->role === 'insurance' && $user->reference_id) {
            return $query->whereHas('surveys', fn ($q) => $q->where('insurance_company_id', $user->reference_id));
        }

        if ($user->role === 'broker' && $user->reference_id) {
            return $query->whereHas('surveys', fn ($q) => $q->where('broker_id', $user->reference_id));
        }

        return $query;
    }

    public static function canViewAny(): bool
    {
        $role = Auth::user()?->role;
        return in_array($role, ['admin_mcm', 'shipowner']);
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->role === 'admin_mcm';
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::user()?->role === 'admin_mcm';
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
            'index' => ListVessels::route('/'),
            'create' => CreateVessel::route('/create'),
            'edit' => EditVessel::route('/{record}/edit'),
        ];
    }
}
