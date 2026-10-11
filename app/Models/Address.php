<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    protected $table = 'address';
    protected $primaryKey = 'idAddres';
    public $timestamps = false;

    protected $fillable = ['street', 'number', 'colony', 'state', 'city', 'zip', 'idCostumer'];

    public function costumer(): BelongsTo
    {
        return $this->belongsTo(Costumer::class, 'idCostumer', 'idCostumer');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'idAddress', 'idAddres');
    }
}
