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
            CreateAction::make()->label('+ Buat Penugasan Survei Baru'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Survei')
                ->badge(fn () => \App\Models\Survey::count()),
            'underwriting' => Tab::make('Underwriting')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('condition_type', 'Underwriting'))
                ->badge(fn () => \App\Models\Survey::where('condition_type', 'Underwriting')->count()),
            'self_assessment' => Tab::make('Self Assessment')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('condition_type', 'Self Assessment'))
                ->badge(fn () => \App\Models\Survey::where('condition_type', 'Self Assessment')->count()),
            'photo_album' => Tab::make('Photo Album')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('condition_type', 'Photo Album'))
                ->badge(fn () => \App\Models\Survey::where('condition_type', 'Photo Album')->count()),
            'survey' => Tab::make('Survey')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('condition_type', 'Survey'))
                ->badge(fn () => \App\Models\Survey::where('condition_type', 'Survey')->count()),
        ];
    }
}
