<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'product_name',
        'product_photo',
        'product_price',
        'product_description',
        'product_stock',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'product_price' => 'decimal:2',
            'product_stock' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Product belongs to a category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Product has many order details.
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'product_id');
    }

    /**
     * Accessor for formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->product_price, 0, ',', '.');
    }

    /**
     * Accessor for photo url with fallback placeholder.
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->product_photo && Storage::disk('public')->exists($this->product_photo)) {
            return asset('storage/' . $this->product_photo);
        }
        
        if ($this->product_photo && filter_var($this->product_photo, FILTER_VALIDATE_URL)) {
            return $this->product_photo;
        }

        return asset('images/default-coffee.svg');
    }

    /**
     * Stock status color indicator:
     * Green: stock > 5
     * Yellow: stock <= 5 and > 0
     * Red: stock == 0
     */
    public function getStockBadgeClassAttribute(): string
    {
        if ($this->product_stock <= 0) {
            return 'bg-danger text-white';
        }

        if ($this->product_stock <= 5) {
            return 'bg-warning text-dark';
        }

        return 'bg-success text-white';
    }
}
