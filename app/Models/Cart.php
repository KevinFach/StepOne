<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $table = 'cart';
    protected $primaryKey = 'idCart';
    public $timestamps = false;

    protected $fillable = ['status', 'createdAt', 'expiresAt', 'idCostumer'];

    protected function casts(): array
    {
        return [
            'createdAt' => 'datetime',
            'expiresAt' => 'datetime',
        ];
    }

    public function costumer(): BelongsTo
    {
        return $this->belongsTo(Costumer::class, 'idCostumer', 'idCostumer');
    }

    public function details(): HasMany
    {
        return $this->hasMany(DetailCart::class, 'idCart', 'idCart');
    }
}
