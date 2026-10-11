<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Costumer extends Authenticatable
{
    use Notifiable;

    protected $table = 'costumer';
    protected $primaryKey = 'idCostumer';
    public $timestamps = false;

    protected $fillable = [
        'first_name', 'last_name', 'phone', 'email', 'rfc', 'CURP',
        'password', 'tempPassword', 'is_active', 'is_admin', 'last_login',
    ];

    protected $hidden = ['password', 'tempPassword', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'last_login' => 'datetime',
            'is_active' => 'boolean',
            'is_admin' => 'boolean',
        ];
    }

    public function getIsStaffAttribute(): bool
    {
        return $this->is_admin;
    }

    public function scopeClients(Builder $query): void
    {
        $query->where('is_admin', false);
    }

    public function scopeAdministrators(Builder $query): void
    {
        $query->where('is_admin', true);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'idCostumer', 'idCostumer');
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class, 'idCostumer', 'idCostumer');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'idCostumer', 'idCostumer');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'idCostumer', 'idCostumer');
    }
}
