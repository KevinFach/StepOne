<?php

namespace App\Filament\Resources\DetailOrders\Pages;

use App\Filament\Resources\DetailOrders\DetailOrderResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDetailOrder extends EditRecord
{
    protected static string $resource = DetailOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
