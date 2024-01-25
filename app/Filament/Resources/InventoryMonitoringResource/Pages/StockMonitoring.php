<?php

namespace App\Filament\Resources\InventoryMonitoringResource\Pages;

use App\Filament\Resources\InventoryMonitoringResource;
use App\Models\Product;
use Filament\Resources\Pages\Page;

class StockMonitoring extends Page
{
    protected static string $resource = InventoryMonitoringResource::class;

    public $products;

    protected static string $view = 'filament.resources.inventory-monitoring-resource.pages.stock-monitoring';

    public function mount()
    {
        $this->products = Product::with('inventories')->get();
    }
}
