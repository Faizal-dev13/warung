<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'name', 'sku', 'duration', 'price', 'old_price', 'stock',
        'description', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'price' => 'integer',
        'old_price' => 'integer',
        'stock' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getDiscountAmountAttribute(): int
    {
        return max(0, (int) $this->old_price - (int) $this->price);
    }

    public function getDisplayNameAttribute(): string
    {
        return trim($this->product?->name.' - '.$this->name, ' -');
    }
}
