<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $table = 'review';
    protected $primaryKey = 'idReview';
    public $timestamps = false;

    protected $fillable = ['comment', 'rating', 'createdAt', 'idSneaker', 'idCostumer'];

    protected function casts(): array
    {
        return ['createdAt' => 'datetime'];
    }

    public function sneaker(): BelongsTo
    {
        return $this->belongsTo(Sneaker::class, 'idSneaker', 'idSneaker');
    }

    public function costumer(): BelongsTo
    {
        return $this->belongsTo(Costumer::class, 'idCostumer', 'idCostumer');
    }
}
