<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $table = 'coupon';
    protected $primaryKey = 'idCoupon';
    public $timestamps = false;

    protected $fillable = ['maxUses', 'discountValue', 'code', 'status', 'validFrom', 'validUntil'];

    protected function casts(): array
    {
        return [
            'discountValue' => 'decimal:2',
            'validFrom' => 'date',
            'validUntil' => 'date',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'idCoupon', 'idCoupon');
    }
}
