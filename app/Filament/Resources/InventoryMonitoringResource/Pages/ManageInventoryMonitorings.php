<?php

namespace App\Filament\Resources\InventoryMonitoringResource\Pages;

use App\Filament\Resources\InventoryMonitoringResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageInventoryMonitorings extends ManageRecords
{
    protected static string $resource = InventoryMonitoringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
