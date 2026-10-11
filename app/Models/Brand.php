<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    protected $table = 'Brand';
    protected $primaryKey = 'idBrand';
    public $timestamps = false;

    protected $fillable = ['name', 'description'];

    public function sneakers(): HasMany
    {
        return $this->hasMany(Sneaker::class, 'idBrand', 'idBrand');
    }
}
