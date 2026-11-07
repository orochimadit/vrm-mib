<?php

namespace App\Filament\Resources\VesselTypes\Pages;

use App\Filament\Resources\VesselTypes\VesselTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVesselTypes extends ListRecords
{
    protected static string $resource = VesselTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
