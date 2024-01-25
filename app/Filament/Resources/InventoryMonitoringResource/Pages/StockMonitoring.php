<?php

namespace App\Filament\Resources\InventoryMonitoringResource\Pages;

use App\Filament\Resources\InventoryMonitoringResource;
use App\Models\Product;
use Filament\Resources\Pages\Page;

class StockMonitoring extends Page
{
    protected static string $resource = InventoryMonitoringResource::class;

    public $products;
    public $search;

    protected static string $view = 'filament.resources.inventory-monitoring-resource.pages.stock-monitoring';

    public function updatedSearch()
    {
            $this->products = Product::with('inventories')
            ->when(!empty($this->search), function($query){
                $query->where('barcode', 'like', '%'.$this->search.'%')
                ->orWhere('name', 'like', '%'.$this->search.'%');
            })
            ->get();
    }

    public function mount()
    {
        $this->products = Product::with('inventories')->get();
    }
}
