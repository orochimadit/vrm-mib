<?php

namespace App\Filament\Resources\CasualtyRefs\Pages;

use App\Filament\Resources\CasualtyRefs\CasualtyRefResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCasualtyRefs extends ListRecords
{
    protected static string $resource = CasualtyRefResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create Casualty')
                ->color('primary'),
        ];
    }
}
