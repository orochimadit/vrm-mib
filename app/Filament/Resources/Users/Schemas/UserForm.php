<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),
                TextInput::make('username')
                    ->label('Username')
                    ->unique(ignoreRecord: true)
                    ->maxLength(100),
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Select::make('role')
                    ->label('Role Akses')
                    ->options([
                        'admin_mcm' => 'Admin MCM (Superadmin)',
                        'insurance' => 'Insurance Partner (Underwriter)',
                        'surveyor' => 'Marine Surveyor',
                        'shipowner' => 'Ship Owner (Pemilik Kapal)',
                        'broker' => 'Insurance Broker',
                    ])
                    ->default('admin_mcm')
                    ->required(),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create'),
                Toggle::make('is_active')
                    ->label('Akun Aktif')
                    ->default(true)
                    ->required(),
            ]);
    }
}
