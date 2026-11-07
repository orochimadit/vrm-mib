<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class InsuranceCompanyCardsWidget extends Widget
{
    protected string $view = 'filament.components.insurance-company-cards';

    protected int|string|array $columnSpan = 'full';
}
