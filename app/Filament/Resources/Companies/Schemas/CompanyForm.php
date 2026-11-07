<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Company Name')
                    ->required(),
                TextInput::make('pic')
                    ->label('PIC'),
                TextInput::make('phone')
                    ->label('Phone')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email')
                    ->email(),
                Textarea::make('address')
                    ->label('Address')
                    ->columnSpanFull(),
                TextInput::make('website')
                    ->label('Website')
                    ->url(),
            ]);
    }
}
