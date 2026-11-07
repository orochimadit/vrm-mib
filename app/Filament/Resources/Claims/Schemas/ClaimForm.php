<?php

namespace App\Filament\Resources\Claims\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class ClaimForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('vessel_id')
                    ->relationship('vessel', 'name'),
                Select::make('insurance_company_id')
                    ->relationship('insuranceCompany', 'name'),
                Select::make('insurance_product_id')
                    ->relationship('insuranceProduct', 'name'),
                TextInput::make('portfolio_consultant'),
                Textarea::make('accident')
                    ->columnSpanFull(),
                Select::make('casualty_ref_id')
                    ->relationship('casualtyRef', 'id'),
                DatePicker::make('case_handled'),
                DatePicker::make('date_of_loss'),
                TimePicker::make('time_of_loss'),
                TextInput::make('location'),
                TextInput::make('estimated_claim')
                    ->numeric(),
                TextInput::make('estimated_claim_currency'),
                TextInput::make('amount')
                    ->numeric(),
                TextInput::make('deductible'),
                TextInput::make('deductible_currency'),
                TextInput::make('deductible_input')
                    ->required()
                    ->default('number'),
                TextInput::make('policy_number'),
                DatePicker::make('period_of_policy_start'),
                DatePicker::make('period_of_policy_end'),
                TextInput::make('mic_bm'),
                TextInput::make('mic_clp'),
                TextInput::make('tsi_lol')
                    ->numeric(),
                TextInput::make('tsi_lol_currency'),
                TextInput::make('settled_claim_amount_currency'),
                TextInput::make('settled_claim_amount')
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('ongoing'),
                Textarea::make('status_description')
                    ->columnSpanFull(),
                DatePicker::make('settled_date'),
            ]);
    }
}
