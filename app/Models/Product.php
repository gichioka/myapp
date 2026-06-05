<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'brand',
        'sku',
        'category',
        'cpu',
        'ram',
        'storage',
        'quantity',
        'unit_price',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'ram' => 'integer',
            'quantity' => 'integer',
            'unit_price' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}