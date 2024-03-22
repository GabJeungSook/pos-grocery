<?php

namespace App\Filament\Cashier\Resources\ViewTransactionResource\Pages;

use App\Filament\Cashier\Resources\ViewTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListViewTransactions extends ListRecords
{
    protected static string $resource = ViewTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'regular' => Tab::make('Regular Transactions')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('is_voided', false)),
            'voided' => Tab::make('Voided Transactions')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_voided', true)),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'regular';
    }
}
