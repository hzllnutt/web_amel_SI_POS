<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_code',
        'order_date',
        'order_subtotal',
        'order_tax',
        'order_amount',
        'order_paid',
        'order_change',
        'payment_method',
        'order_status',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'datetime',
            'order_subtotal' => 'decimal:2',
            'order_tax' => 'decimal:2',
            'order_amount' => 'decimal:2',
            'order_paid' => 'decimal:2',
            'order_change' => 'decimal:2',
        ];
    }

    /**
     * Order belongs to a user (cashier/admin).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Order has many order details.
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->order_subtotal, 0, ',', '.');
    }

    public function getFormattedTaxAttribute(): string
    {
        return 'Rp ' . number_format($this->order_tax, 0, ',', '.');
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->order_amount, 0, ',', '.');
    }

    public function getFormattedPaidAttribute(): string
    {
        return 'Rp ' . number_format($this->order_paid, 0, ',', '.');
    }

    public function getFormattedChangeAttribute(): string
    {
        return 'Rp ' . number_format($this->order_change, 0, ',', '.');
    }
}
