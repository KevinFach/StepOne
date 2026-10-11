<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    protected $table = 'purchase';
    protected $primaryKey = 'idPurchase';
    public $timestamps = false;

    protected $fillable = ['invoiceNumber', 'totalAmount', 'paymentMethod', 'purchaseDate', 'idSupplier', 'idEmployee'];

    protected function casts(): array
    {
        return [
            'totalAmount' => 'decimal:2',
            'purchaseDate' => 'datetime',
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'idSupplier', 'idSupplier');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employed::class, 'idEmployee', 'idEmployed');
    }

    public function details(): HasMany
    {
        return $this->hasMany(DetailPurchase::class, 'idPurchase', 'idPurchase');
    }
}
