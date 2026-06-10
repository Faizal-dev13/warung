<?php

namespace App\Models;

use App\Models\Concerns\ClearsPublicCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Voucher extends Model
{
    use ClearsPublicCache;
    use HasFactory;

    protected $fillable = ['code', 'label', 'type', 'value', 'minimum_order', 'is_active', 'starts_at', 'ends_at'];

    protected $casts = [
        'value' => 'integer',
        'minimum_order' => 'integer',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];


    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }

    public function appliesToAllProducts(): bool
    {
        if ($this->relationLoaded('products')) {
            return $this->products->isEmpty();
        }

        return ! $this->products()->exists();
    }

    public function isUsableFor(int $subtotal): bool
    {
        if (! $this->is_active || $subtotal < $this->minimum_order) {
            return false;
        }

        $now = now();

        return (! $this->starts_at || $this->starts_at->lte($now))
            && (! $this->ends_at || $this->ends_at->gte($now));
    }

    public function discountFor(int $subtotal): int
    {
        if (! $this->isUsableFor($subtotal)) {
            return 0;
        }

        return $this->calculateDiscount($subtotal);
    }

    public function eligibleSubtotalForCart(array $cart): int
    {
        $productIds = $this->relationLoaded('products')
            ? $this->products->pluck('id')->map(fn ($id) => (int) $id)->all()
            : $this->products()->pluck('products.id')->map(fn ($id) => (int) $id)->all();

        return collect($cart)->sum(function ($item) use ($productIds) {
            $productId = (int) ($item['product_id'] ?? $item['id'] ?? 0);

            if ($productIds !== [] && ! in_array($productId, $productIds, true)) {
                return 0;
            }

            return (int) ($item['price'] ?? 0) * (int) ($item['qty'] ?? 0);
        });
    }

    public function discountForCart(array $cart): int
    {
        $eligibleSubtotal = $this->eligibleSubtotalForCart($cart);

        if (! $this->isUsableFor($eligibleSubtotal)) {
            return 0;
        }

        return $this->calculateDiscount($eligibleSubtotal);
    }

    private function calculateDiscount(int $subtotal): int
    {
        if ($subtotal <= 0) {
            return 0;
        }

        return $this->type === 'fixed'
            ? min((int) $this->value, $subtotal)
            : min($subtotal, (int) floor($subtotal * ((int) $this->value / 100)));
    }
}
