<?php

namespace App\Filament\Resources\TaxResource\Pages;

use App\Filament\Resources\TaxResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTaxes extends ListRecords
{
    protected static string $resource = TaxResource::class;
    protected static ?string $title = 'Tax';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->visible(fn (Builder $query) => $query->count() === 0),
        ];
    }
}
