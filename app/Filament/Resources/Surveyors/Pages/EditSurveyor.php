<?php

namespace App\Filament\Resources\Surveyors\Pages;

use App\Filament\Resources\Surveyors\SurveyorResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSurveyor extends EditRecord
{
    protected static string $resource = SurveyorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
