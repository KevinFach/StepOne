<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'Category';
    protected $primaryKey = 'idCategory';
    public $timestamps = false;

    protected $fillable = ['name', 'description'];

    public function sneakers(): HasMany
    {
        return $this->hasMany(Sneaker::class, 'idCategory', 'idCategory');
    }
}
