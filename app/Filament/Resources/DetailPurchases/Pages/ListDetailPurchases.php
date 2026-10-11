<?php

namespace App\Filament\Resources\DetailPurchases\Pages;

use App\Filament\Resources\DetailPurchases\DetailPurchaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDetailPurchases extends ListRecords
{
    protected static string $resource = DetailPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
