<?php

namespace App\Filament\Resources\DetailOrders\Pages;

use App\Filament\Resources\DetailOrders\DetailOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDetailOrder extends CreateRecord
{
    protected static string $resource = DetailOrderResource::class;
}
