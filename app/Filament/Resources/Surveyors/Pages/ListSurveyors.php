<?php

namespace App\Filament\Resources\Surveyors\Pages;

use App\Filament\Resources\Surveyors\SurveyorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSurveyors extends ListRecords
{
    protected static string $resource = SurveyorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add New Surveyor')
                ->color('primary'),
        ];
    }
}
