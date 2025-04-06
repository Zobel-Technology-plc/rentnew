<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    protected $fillable = [
        'name',
        'address',
        'type',
        'status',
        'price_per_month'
    ];

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }
} 