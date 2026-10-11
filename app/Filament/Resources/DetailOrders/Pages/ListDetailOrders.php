<?php

namespace App\Filament\Resources\DetailOrders\Pages;

use App\Filament\Resources\DetailOrders\DetailOrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDetailOrders extends ListRecords
{
    protected static string $resource = DetailOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
