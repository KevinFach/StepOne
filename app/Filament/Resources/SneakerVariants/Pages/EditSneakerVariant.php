<?php

namespace App\Filament\Resources\SneakerVariants\Pages;

use App\Filament\Resources\SneakerVariants\SneakerVariantResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSneakerVariant extends EditRecord
{
    protected static string $resource = SneakerVariantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
