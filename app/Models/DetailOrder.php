<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailOrder extends Model
{
    protected $table = 'detailOrder';
    protected $primaryKey = 'idDetailOrder';
    public $timestamps = false;

    protected $fillable = ['quantity', 'unitPrice', 'idSneakerVariant', 'idOrder'];

    protected function casts(): array
    {
        return ['unitPrice' => 'decimal:2'];
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(SneakerVariant::class, 'idSneakerVariant', 'idSneakerVariant');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'idOrder', 'idOrder');
    }
}
