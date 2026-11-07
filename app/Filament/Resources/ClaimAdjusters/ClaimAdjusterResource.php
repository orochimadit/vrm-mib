<?php

namespace App\Filament\Resources\ClaimAdjusters;

use App\Filament\Resources\ClaimAdjusters\Pages\CreateClaimAdjuster;
use App\Filament\Resources\ClaimAdjusters\Pages\EditClaimAdjuster;
use App\Filament\Resources\ClaimAdjusters\Pages\ListClaimAdjusters;
use App\Filament\Resources\ClaimAdjusters\Schemas\ClaimAdjusterForm;
use App\Filament\Resources\ClaimAdjusters\Tables\ClaimAdjustersTable;
use App\Models\ClaimAdjuster;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ClaimAdjusterResource extends Resource
{
    protected static ?string $model = ClaimAdjuster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Adjusters';

    protected static \UnitEnum|string|null $navigationGroup = 'Insurance';

    protected static ?string $modelLabel = 'Adjuster';

    protected static ?string $pluralModelLabel = 'Adjusters';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return Auth::user()?->role === 'admin_mcm';
    }

    public static function form(Schema $schema): Schema
    {
        return ClaimAdjusterForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClaimAdjustersTable::configure($table);
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
            'index' => ListClaimAdjusters::route('/'),
            'create' => CreateClaimAdjuster::route('/create'),
            'edit' => EditClaimAdjuster::route('/{record}/edit'),
        ];
    }
}
