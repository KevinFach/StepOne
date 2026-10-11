<?php

namespace App\Filament\Resources\DetailCarts\Pages;

use App\Filament\Resources\DetailCarts\DetailCartResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDetailCarts extends ListRecords
{
    protected static string $resource = DetailCartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
