<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailCart extends Model
{
    protected $table = 'detailCart';
    protected $primaryKey = 'idDetailCart';
    public $timestamps = false;

    protected $fillable = ['quantity', 'priceAtAddition', 'idSneakerVariant', 'idCart'];

    protected function casts(): array
    {
        return ['priceAtAddition' => 'decimal:2'];
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(SneakerVariant::class, 'idSneakerVariant', 'idSneakerVariant');
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class, 'idCart', 'idCart');
    }
}
