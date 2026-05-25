<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'summary', 'description', 'price', 'old_price',
        'badge', 'image_path', 'icon', 'accent', 'features', 'is_latest', 'is_featured', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'integer',
        'old_price' => 'integer',
        'is_latest' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getDiscountAmountAttribute(): int
    {
        return max(0, (int) $this->old_price - (int) $this->price);
    }
}
