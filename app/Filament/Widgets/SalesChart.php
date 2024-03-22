<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use Flowframe\Trend\Trend;
use App\Models\Transaction;
use Flowframe\Trend\TrendValue;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Overall Sales';

    protected function getData(): array
    {
        $data = Trend::query(Transaction::query()->where('is_voided', 0))
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->sum('grand_total');

    return [
        'datasets' => [
            [
                'label' => 'Sales Per Month',
                'data' => $data->map(fn ($item) => $item->aggregate),
            ],
        ],
        'labels' => $data->map(fn (TrendValue $value) => Carbon::parse($value->date)->format('M')),
    ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
