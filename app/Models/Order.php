<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'idOrder';
    public $timestamps = false;

    protected $fillable = [
        'status', 'createdAt', 'shippingCost', 'totalAmount', 'shippingCompany',
        'trackingNumber', 'idAddress', 'idCostumer', 'idCoupon',
    ];

    protected function casts(): array
    {
        return [
            'createdAt' => 'datetime',
            'shippingCost' => 'decimal:2',
            'totalAmount' => 'decimal:2',
        ];
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'idAddress', 'idAddres');
    }

    public function costumer(): BelongsTo
    {
        return $this->belongsTo(Costumer::class, 'idCostumer', 'idCostumer');
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'idCoupon', 'idCoupon');
    }

    public function details(): HasMany
    {
        return $this->hasMany(DetailOrder::class, 'idOrder', 'idOrder');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'idOrder', 'idOrder');
    }
}
