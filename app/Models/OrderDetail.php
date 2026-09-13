<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'order_quantity',
        'order_price',
        'order_subtotal',
    ];

    protected function casts(): array
    {
        return [
            'order_quantity' => 'integer',
            'order_price' => 'decimal:2',
            'order_subtotal' => 'decimal:2',
        ];
    }

    /**
     * Order detail belongs to an order.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Order detail belongs to a product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->order_price, 0, ',', '.');
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->order_subtotal, 0, ',', '.');
    }
}
