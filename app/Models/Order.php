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
        'name',
        'address',
        'phone',
        'total_price',
        'status',
        'shipping_status',
        'ghn_order_code',
        'ghn_total_fee',
        'to_district_id',
        'to_ward_code',
    ];

    protected function casts(): array
    {
        return [
            'total_price' => 'decimal:2',
            'ghn_total_fee' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function paymentTransactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function latestPayment(): ?PaymentTransaction
    {
        return $this->paymentTransactions()->latest()->first();
    }

    public function canPayAgain(): bool
    {
        return in_array($this->status, ['pending'], true)
            && ! $this->ghn_order_code
            && $this->latestPayment()?->gateway === 'momo'
            && $this->latestPayment()?->status !== 'paid';
    }
}
