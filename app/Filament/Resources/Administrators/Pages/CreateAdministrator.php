<?php

namespace App\Filament\Resources\Administrators\Pages;

use App\Filament\Resources\Administrators\AdministratorResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAdministrator extends CreateRecord
{
    protected static string $resource = AdministratorResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['is_admin'] = true;

        return $data;
    }
}
