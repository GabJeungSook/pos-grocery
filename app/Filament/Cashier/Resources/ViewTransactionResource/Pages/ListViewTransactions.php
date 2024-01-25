<?php

namespace App\Filament\Cashier\Resources\ViewTransactionResource\Pages;

use App\Filament\Cashier\Resources\ViewTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListViewTransactions extends ListRecords
{
    protected static string $resource = ViewTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
