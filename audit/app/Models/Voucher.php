<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'label', 'type', 'value', 'minimum_order', 'is_active', 'starts_at', 'ends_at'];

    protected $casts = [
        'value' => 'integer',
        'minimum_order' => 'integer',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

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

        return $this->type === 'fixed'
            ? min($this->value, $subtotal)
            : (int) floor($subtotal * ($this->value / 100));
    }
}
