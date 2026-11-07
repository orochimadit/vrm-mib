<?php

namespace App\Filament\Resources\Surveys\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SurveyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('vessel_id')
                    ->relationship('vessel', 'name')
                    ->required(),
                Select::make('surveyor_id')
                    ->relationship('surveyor', 'name'),
                Select::make('broker_id')
                    ->relationship('broker', 'name'),
                Select::make('insurance_company_id')
                    ->relationship('insuranceCompany', 'name'),
                TextInput::make('surveyor_name'),
                TextInput::make('condition_type')
                    ->required()
                    ->default('Survey'),
                TextInput::make('status')
                    ->required()
                    ->default('Outstanding'),
                TextInput::make('form_status')
                    ->required()
                    ->default('draft'),
                TextInput::make('location'),
                DatePicker::make('date_of_survey'),
                DatePicker::make('due_date'),
                Textarea::make('concern_from_surveyor')
                    ->columnSpanFull(),
                Textarea::make('resume_from_surveyor')
                    ->columnSpanFull(),
                Textarea::make('summary')
                    ->columnSpanFull(),
                DatePicker::make('policy_period_start'),
                DatePicker::make('policy_period_end'),
                TextInput::make('inspection_data'),
                TextInput::make('risk_score')
                    ->numeric(),
                TextInput::make('risk_rating'),
                TextInput::make('created_by')
                    ->numeric(),
            ]);
    }
}
