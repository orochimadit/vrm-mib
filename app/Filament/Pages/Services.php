<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class Services extends Page
{
    protected string $view = 'filament.pages.services';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static ?string $navigationLabel = 'Services';

    protected static ?string $title = 'Services Hub';

    protected static ?int $navigationSort = 1;
}
