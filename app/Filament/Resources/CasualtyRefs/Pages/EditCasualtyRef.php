<?php

namespace App\Filament\Resources\CasualtyRefs\Pages;

use App\Filament\Resources\CasualtyRefs\CasualtyRefResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCasualtyRef extends EditRecord
{
    protected static string $resource = CasualtyRefResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
