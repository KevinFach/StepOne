<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPurchase extends Model
{
    protected $table = 'detailPurchase';
    protected $primaryKey = 'idDetailPurchase';
    public $timestamps = false;

    protected $fillable = ['quantity', 'unitCost', 'idSneakerVariant', 'idPurchase'];

    protected function casts(): array
    {
        return ['unitCost' => 'decimal:2'];
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(SneakerVariant::class, 'idSneakerVariant', 'idSneakerVariant');
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class, 'idPurchase', 'idPurchase');
    }
}
