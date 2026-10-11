<?php

namespace App\Filament\Resources\DetailPurchases\Pages;

use App\Filament\Resources\DetailPurchases\DetailPurchaseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDetailPurchase extends EditRecord
{
    protected static string $resource = DetailPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
