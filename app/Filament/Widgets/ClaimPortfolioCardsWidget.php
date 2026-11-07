<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class ClaimPortfolioCardsWidget extends Widget
{
    protected string $view = 'filament.components.claim-portfolio-cards';

    protected int|string|array $columnSpan = 'full';
}
