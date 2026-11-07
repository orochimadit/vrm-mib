<?php

namespace App\Filament\Resources\Surveys\Pages;

use App\Filament\Resources\Surveys\SurveyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListSurveys extends ListRecords
{
    protected static string $resource = SurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('+ Buat Penugasan Survei Baru')
                ->visible(fn () => SurveyResource::canCreate()),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'self_assessment';
    }

    public function getTabs(): array
    {
        return [
            'underwriting' => Tab::make('Underwriting')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('condition_type', 'Underwriting')),
            'self_assessment' => Tab::make('Self Assessment')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('condition_type', 'Self Assessment')),
            'photo_album' => Tab::make('Photo Album')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('condition_type', 'Photo Album')),
            'survey' => Tab::make('Survey')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('condition_type', 'Survey')),
            'all' => Tab::make('All'),
        ];
    }
}
