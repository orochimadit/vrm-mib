<?php

namespace App\Filament\Resources\Claims\Pages;

use App\Filament\Resources\Claims\ClaimResource;
use App\Filament\Widgets\ClaimPortfolioCardsWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClaims extends ListRecords
{
    protected static string $resource = ClaimResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add New Claim Case')
                ->color('success'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ClaimPortfolioCardsWidget::class,
        ];
    }
}
