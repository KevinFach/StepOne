<?php

namespace App\Filament\Resources\Employeds\Pages;

use App\Filament\Resources\Employeds\EmployedResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEmployed extends EditRecord
{
    protected static string $resource = EmployedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
