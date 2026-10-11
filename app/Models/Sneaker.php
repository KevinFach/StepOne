<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sneaker extends Model
{
    public const ACTIVE = 1;
    public const INACTIVE = 0;

    protected $table = 'sneaker';
    protected $primaryKey = 'idSneaker';
    public $timestamps = false;

    protected $fillable = ['name', 'idBrand', 'description', 'idCategory', 'estado', 'image'];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'idBrand', 'idBrand');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'idCategory', 'idCategory');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(SneakerVariant::class, 'idSneaker', 'idSneaker');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'idSneaker', 'idSneaker');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/'.$this->image) : null;
    }
}
