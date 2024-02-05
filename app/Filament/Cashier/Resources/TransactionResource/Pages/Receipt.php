<?php

namespace App\Filament\Cashier\Resources\TransactionResource\Pages;

use App\Models\Transaction;
use Filament\Resources\Pages\Page;
use App\Filament\Cashier\Resources\TransactionResource;

class Receipt extends Page
{
    protected static string $resource = TransactionResource::class;
    protected static string $view = 'filament.cashier.resources.transaction-resource.pages.receipt';

    public $record;
    public $transaction;

    public function mount()
    {
        $this->transaction = Transaction::find($this->record);
    }
}
