<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'stock_quantity', 'price'];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function requestItems(): HasMany
    {
        return $this->hasMany(RequestItem::class);
    }
}
