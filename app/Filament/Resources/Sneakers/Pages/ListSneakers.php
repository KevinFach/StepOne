<?php

namespace App\Filament\Resources\Sneakers\Pages;

use App\Filament\Resources\Sneakers\SneakerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSneakers extends ListRecords
{
    protected static string $resource = SneakerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
