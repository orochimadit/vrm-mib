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
                    ->required(),
                TextInput::make('ex_name'),
                TextInput::make('imo_number'),
                TextInput::make('flag'),
                Select::make('vessel_type_id')
                    ->relationship('vesselType', 'name'),
                TextInput::make('type_name_legacy'),
                Select::make('owner_company_id')
                    ->relationship('ownerCompany', 'name'),
                Select::make('operator_company_id')
                    ->relationship('operatorCompany', 'name'),
                TextInput::make('classification_society'),
                TextInput::make('class_recommendation_issued'),
                TextInput::make('exemption_certificates_issued'),
                TextInput::make('year_built')
                    ->numeric(),
                TextInput::make('place_built'),
                TextInput::make('grt')
                    ->numeric(),
                TextInput::make('dwt')
                    ->numeric(),
                TextInput::make('tsi')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('dimensions'),
                Textarea::make('recent_cargoes_carried')
                    ->columnSpanFull(),
                TextInput::make('recent_port_of_call'),
                TextInput::make('trading_area'),
                DatePicker::make('date_of_last_drydock'),
                DatePicker::make('date_of_next_drydock'),
            ]);
    }
}
