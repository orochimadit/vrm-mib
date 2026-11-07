<?php

namespace App\Filament\Resources\SurveyDefects\Pages;

use App\Filament\Resources\SurveyDefects\SurveyDefectResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSurveyDefects extends ListRecords
{
    protected static string $resource = SurveyDefectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
