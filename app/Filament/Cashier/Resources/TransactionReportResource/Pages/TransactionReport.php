<?php

namespace App\Filament\Cashier\Resources\TransactionReportResource\Pages;

use App\Models\Transaction;
use Filament\Resources\Pages\Page;
use App\Filament\Cashier\Resources\TransactionReportResource;

class TransactionReport extends Page
{
    protected static string $resource = TransactionReportResource::class;
    protected static string $view = 'filament.cashier.resources.transaction-report-resource.pages.transaction-report';
    public $record;

    public function mount(): void
    {

        $this->record = Transaction::get();
        static::authorizeResourceAccess();
    }
}
