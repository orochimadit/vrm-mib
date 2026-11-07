<?php

namespace App\Filament\Resources\SurveyDefects\Pages;

use App\Filament\Resources\SurveyDefects\SurveyDefectResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSurveyDefect extends EditRecord
{
    protected static string $resource = SurveyDefectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
