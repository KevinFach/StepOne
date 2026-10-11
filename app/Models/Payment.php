<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $table = 'payment';
    protected $primaryKey = 'idPayment';
    public $timestamps = false;

    protected $fillable = ['idOrder', 'payment_date', 'amount', 'method', 'status'];

    protected function casts(): array
    {
        return [
            'payment_date' => 'datetime',
            'amount' => 'decimal:2',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'idOrder', 'idOrder');
    }
}
