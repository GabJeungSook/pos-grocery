<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CashierOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Products', '1,250'),
            Stat::make('Sales Today', '₱ 5,000.00'),
            Stat::make('Overall Sales', '₱ 19,160.00'),
        ];
    }
}
