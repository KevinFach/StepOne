<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SneakerVariant extends Model
{
    protected $table = 'sneakerVariant';
    protected $primaryKey = 'idSneakerVariant';
    public $timestamps = false;

    protected $fillable = ['size', 'gender', 'color', 'price', 'stock', 'idSneaker', 'image'];

    protected function casts(): array
    {
        return ['price' => 'decimal:2'];
    }

    public function sneaker(): BelongsTo
    {
        return $this->belongsTo(Sneaker::class, 'idSneaker', 'idSneaker');
    }

    /**
     * Texto para identificar la variante en el panel: "Nike Cortez - Rosa - 25".
     */
    public function getLabelAttribute(): string
    {
        return "{$this->sneaker?->name} - {$this->color} - {$this->size}";
    }

    /**
     * Imagen de la variante; si no tiene, la del sneaker principal.
     */
    public function getImage(): ?string
    {
        if ($this->image) {
            return asset('storage/'.$this->image);
        }

        return $this->sneaker?->image_url;
    }
}
