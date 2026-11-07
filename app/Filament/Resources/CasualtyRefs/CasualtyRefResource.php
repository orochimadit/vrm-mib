<?php

namespace App\Filament\Resources\CasualtyRefs;

use App\Filament\Resources\CasualtyRefs\Pages\CreateCasualtyRef;
use App\Filament\Resources\CasualtyRefs\Pages\EditCasualtyRef;
use App\Filament\Resources\CasualtyRefs\Pages\ListCasualtyRefs;
use App\Filament\Resources\CasualtyRefs\Schemas\CasualtyRefForm;
use App\Filament\Resources\CasualtyRefs\Tables\CasualtyRefsTable;
use App\Models\CasualtyRef;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class CasualtyRefResource extends Resource
{
    protected static ?string $model = CasualtyRef::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCircleStack;

    protected static ?string $navigationLabel = 'Casualty Reference';

    protected static ?string $modelLabel = 'Casualty';

    protected static ?string $pluralModelLabel = 'Casualty';

    protected static ?int $navigationSort = 4;

    public static function canViewAny(): bool
    {
        return Auth::user()?->role === 'admin_mcm';
    }

    public static function form(Schema $schema): Schema
    {
        return CasualtyRefForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CasualtyRefsTable::configure($table);
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
            'index' => ListCasualtyRefs::route('/'),
            'create' => CreateCasualtyRef::route('/create'),
            'edit' => EditCasualtyRef::route('/{record}/edit'),
        ];
    }
}
