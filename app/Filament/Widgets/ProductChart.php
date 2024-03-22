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
    protected static ?string $heading = 'Product Sales by Category';

    protected function getData(): array
    {
        // $products = Product::with('inventories')->get();
        // $datasets = [];
        // $allLabels = [];

        // foreach ($products as $product) {
        //     $data = TransactionItem::where('product_id', $product->id)
        //         ->whereBetween('created_at', [
        //             now()->startOfYear(),
        //             now()->endOfYear(),
        //         ])
        //         ->selectRaw('DATE_FORMAT(created_at, "%b") as month, SUM(quantity) as total_quantity')
        //         ->groupBy('month')
        //         ->get();

        //     if ($data->isNotEmpty()) { // Check if data exists
        //         $datasets[] = [
        //             'label' => $product->name,
        //             'data' => $data->pluck('total_quantity')->toArray(),
        //         ];

        //         $productLabels = $data->pluck('month')->toArray();
        //         $allLabels[] = $productLabels; // Store labels for each product
        //     }
        // }

        // // Flatten and get unique labels
        // $commonLabels = array_unique(array_merge(...$allLabels));

        // if (!empty($datasets)) { // Check if datasets are not empty
        //     $result = [
        //         'datasets' => $datasets,
        //         'labels' => $data->map(fn ($value) => Carbon::parse($value->date)->format('M')),
        //     ];
        // } else {
        //     $result = [];
        // }

        // return $result;

        //by category

        $categories = DB::table('categories')
            ->select('categories.name as category', DB::raw('SUM(transactions.grand_total) as total_sales'))
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('transaction_items', 'products.id', '=', 'transaction_items.product_id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
        ->where('transactions.is_voided', false)
            ->whereBetween('transactions.created_at', [
                now()->startOfYear(),
                now()->endOfYear(),
            ])
            ->groupBy('categories.name')
            ->get();

        $datasets = [
            [
                'label' => 'Total Sales',
                'data' => $categories->pluck('total_sales')->toArray(),
            ],
        ];

        $labels = $categories->pluck('category')->toArray();

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];


    }

    protected function getType(): string
    {
        return 'bar';
    }
}
