<?php

namespace App\Filament\Resources\InsuranceProducts\Pages;

use App\Filament\Resources\InsuranceProducts\InsuranceProductResource;
use App\Filament\Widgets\InsuranceCompanyCardsWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInsuranceProducts extends ListRecords
{
    protected static string $resource = InsuranceProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add New Product')
                ->color('primary'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            InsuranceCompanyCardsWidget::class,
        ];
    }
}
