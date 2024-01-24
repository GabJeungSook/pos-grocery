<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected static ?string $title  = 'Add Cashier';

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


    protected function mutateFormDataBeforeCreate(array $data): array
    {
            $data['role'] = 'cashier';
            return $data;
    }
}
