<?php

namespace App\Filament\Resources\ClaimAdjusters\Pages;

use App\Filament\Resources\ClaimAdjusters\ClaimAdjusterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClaimAdjusters extends ListRecords
{
    protected static string $resource = ClaimAdjusterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add New Adjuster')
                ->color('primary'),
        ];
    }
}
