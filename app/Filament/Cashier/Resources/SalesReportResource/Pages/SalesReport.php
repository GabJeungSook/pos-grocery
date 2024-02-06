<?php

namespace App\Filament\Cashier\Resources\SalesReportResource\Pages;

use App\Models\Transaction;
use Filament\Resources\Pages\Page;
use App\Filament\Cashier\Resources\SalesReportResource;

class SalesReport extends Page
{
    protected static string $resource = SalesReportResource::class;
    protected static string $view = 'filament.cashier.resources.sales-report-resource.pages.sales-report';
    public $record;


    public function mount(): void
    {

        $this->record = Transaction::whereDate('created_at', now())->get();
        static::authorizeResourceAccess();
    }
}
