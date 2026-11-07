<?php

namespace App\Filament\Resources\ClaimAdjusters\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ClaimAdjusterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Adjuster Company Name')
                    ->placeholder('Enter Adjuster Company Name')
                    ->required(),
                TextInput::make('phone')
                    ->label('Phone')
                    ->placeholder('Enter Contact Information')
                    ->tel(),
                TextInput::make('address')
                    ->label('Address')
                    ->placeholder('Enter Address'),
                TextInput::make('email')
                    ->label('Email')
                    ->placeholder('Enter Email')
                    ->email(),
                TextInput::make('website')
                    ->label('Website')
                    ->placeholder('Enter Website URL')
                    ->url(),
            ]);
    }
}
