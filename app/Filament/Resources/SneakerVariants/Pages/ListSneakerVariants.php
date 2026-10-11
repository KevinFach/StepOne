<?php

namespace App\Filament\Resources\SneakerVariants\Pages;

use App\Filament\Resources\SneakerVariants\SneakerVariantResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSneakerVariants extends ListRecords
{
    protected static string $resource = SneakerVariantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
