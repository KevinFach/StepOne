<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employed extends Model
{
    protected $table = 'employed';
    protected $primaryKey = 'idEmployed';
    public $timestamps = false;

    protected $fillable = ['first_name', 'last_name', 'email', 'phone', 'status', 'RFC', 'password', 'position'];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class, 'idEmployee', 'idEmployed');
    }
}
