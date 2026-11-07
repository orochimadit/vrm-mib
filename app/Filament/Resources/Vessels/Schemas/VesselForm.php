<?php

namespace App\Filament\Resources\Vessels\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class VesselForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required(),
                Select::make('vessel_type_id')
                    ->label('Type')
                    ->placeholder('Select Vessel Type')
                    ->relationship('vesselType', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('owner_company_id')
                    ->label('Company')
                    ->placeholder('::Select Company::')
                    ->relationship('ownerCompany', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('year_built')
                    ->label('Year Built')
                    ->numeric(),
                TextInput::make('tsi')
                    ->label('Total Sum Insured (USD)')
                    ->numeric()
                    ->default(0),
                TextInput::make('grt')
                    ->label('GRT')
                    ->numeric(),
                TextInput::make('imo_number')
                    ->label('IMO Number'),
                TextInput::make('flag')
                    ->label('Flag'),
                TextInput::make('classification_society')
                    ->label('Classification Society'),
            ]);
    }
}
