<?php

namespace App\Filament\Resources\Sneakers\Pages;

use App\Filament\Resources\Sneakers\SneakerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSneaker extends EditRecord
{
    protected static string $resource = SneakerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
