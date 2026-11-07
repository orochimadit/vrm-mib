<?php

namespace App\Filament\Resources\VesselTypes;

use App\Filament\Resources\VesselTypes\Pages\CreateVesselType;
use App\Filament\Resources\VesselTypes\Pages\EditVesselType;
use App\Filament\Resources\VesselTypes\Pages\ListVesselTypes;
use App\Filament\Resources\VesselTypes\Schemas\VesselTypeForm;
use App\Filament\Resources\VesselTypes\Tables\VesselTypesTable;
use App\Models\VesselType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VesselTypeResource extends Resource
{
    protected static ?string $model = VesselType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $navigationLabel = 'Vessel Type';

    protected static ?string $modelLabel = 'Tipe Kapal';

    protected static ?string $pluralModelLabel = 'Vessel Type';

    protected static ?int $navigationSort = 8;

    public static function form(Schema $schema): Schema
    {
        return VesselTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VesselTypesTable::configure($table);
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
            'index' => ListVesselTypes::route('/'),
            'create' => CreateVesselType::route('/create'),
            'edit' => EditVesselType::route('/{record}/edit'),
        ];
    }
}
