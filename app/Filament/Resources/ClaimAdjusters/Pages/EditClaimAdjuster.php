<?php

namespace App\Filament\Resources\ClaimAdjusters\Pages;

use App\Filament\Resources\ClaimAdjusters\ClaimAdjusterResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditClaimAdjuster extends EditRecord
{
    protected static string $resource = ClaimAdjusterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
