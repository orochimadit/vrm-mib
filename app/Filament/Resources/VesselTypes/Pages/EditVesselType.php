<?php

namespace App\Filament\Resources\VesselTypes\Pages;

use App\Filament\Resources\VesselTypes\VesselTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVesselType extends EditRecord
{
    protected static string $resource = VesselTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
