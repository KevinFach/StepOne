<?php

namespace App\Filament\Resources\Employeds\Pages;

use App\Filament\Resources\Employeds\EmployedResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployeds extends ListRecords
{
    protected static string $resource = EmployedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
