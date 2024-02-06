<?php

namespace App\Filament\Cashier\Resources\SalesReportResource\Pages;

use App\Filament\Cashier\Resources\SalesReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSalesReports extends ManageRecords
{
    protected static string $resource = SalesReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
