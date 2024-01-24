<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

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
