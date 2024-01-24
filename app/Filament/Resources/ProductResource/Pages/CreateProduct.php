<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
    protected static ?string $title = 'Add Product';

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Save'),
            ...(static::canCreateAnother() ? [$this->getCreateAnotherFormAction()->label('Save & Add Another')] : []),
            $this->getCancelFormAction(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
