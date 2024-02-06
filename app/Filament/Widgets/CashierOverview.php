<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class CashierOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Products', Product::whereHas('inventories')->count())
            ->description('Number of products with available stocks')
            ->icon('heroicon-m-shopping-cart')
            ->color('primary'),
            Stat::make('Sales Today', '₱ '.Transaction::whereDate('created_at', now())->sum('amount_paid'))
            ->icon('heroicon-m-currency-dollar')
            ->description('Total sales today'),
            Stat::make('Overall Sales', '₱ '.Transaction::sum('amount_paid'))
            ->icon('heroicon-m-currency-dollar')
            ->description('Total overall sales'),
        ];
    }
}
