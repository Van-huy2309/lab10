<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'image',
        'original_price',
        'voucher_percent',
        'voucher_code',
        'is_active',
    ];

    protected $casts = [
        'original_price' => 'decimal:0',
        'voucher_percent' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function getSalePriceAttribute(): float
    {
        $discount = (float) $this->voucher_percent;

        if ($discount <= 0) {
            return (float) $this->original_price;
        }

        return round((float) $this->original_price * (1 - $discount / 100), 0);
    }

    public function getDiscountAmountAttribute(): float
    {
        return max(0, (float) $this->original_price - $this->sale_price);
    }

    public function hasVoucher(): bool
    {
        return (float) $this->voucher_percent > 0;
    }

    public function formattedOriginalPrice(): string
    {
        return number_format((float) $this->original_price, 0, ',', '.') . ' đ';
    }

    public function formattedSalePrice(): string
    {
        return number_format($this->sale_price, 0, ',', '.') . ' đ';
    }

    public function imageUrl(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
