<?php

namespace App\Filament\Resources\DetailCarts\Pages;

use App\Filament\Resources\DetailCarts\DetailCartResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDetailCart extends EditRecord
{
    protected static string $resource = DetailCartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
