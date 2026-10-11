<?php

namespace App\Filament\Resources\Sneakers\Pages;

use App\Filament\Resources\Sneakers\SneakerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSneaker extends CreateRecord
{
    protected static string $resource = SneakerResource::class;
}
