<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Carbon\Carbon;
use Flowframe\Trend\Trend;
use App\Models\Transaction;
use App\Models\TransactionItem;

use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class ProductChart extends ChartWidget
{
    protected static ?string $heading = 'Product Sales';

    protected function getData(): array
    {
        $products = Product::with('inventories')->get();
        $datasets = [];
        $allLabels = [];

        foreach ($products as $product) {
            $data = TransactionItem::where('product_id', $product->id)
                ->whereBetween('created_at', [
                    now()->startOfYear(),
                    now()->endOfYear(),
                ])
                ->selectRaw('DATE_FORMAT(created_at, "%b") as month, SUM(quantity) as total_quantity')
                ->groupBy('month')
                ->get();

            $datasets[] = [
                'label' => $product->name,
                'data' => $data->pluck('total_quantity')->toArray(),
            ];

            $productLabels = $data->pluck('month')->toArray();
            $allLabels[] = $productLabels; // Store labels for each product
        }

        // Flatten and get unique labels
        $commonLabels = array_unique(array_merge(...$allLabels));

        $result = [
            'datasets' => $datasets,
            'labels' => $data === null ? '' : $data->map(fn ($value) => Carbon::parse($value->date)->format('M')),
        ];

        return $result;

    }

    protected function getType(): string
    {
        return 'bar';
    }
}
