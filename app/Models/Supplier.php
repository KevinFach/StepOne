<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $table = 'supplier';
    protected $primaryKey = 'idSupplier';
    public $timestamps = false;

    protected $fillable = ['name', 'phone', 'address', 'email'];

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class, 'idSupplier', 'idSupplier');
    }
}
