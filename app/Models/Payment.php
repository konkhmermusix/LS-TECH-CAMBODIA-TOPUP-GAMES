<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';
    public const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'order_id',
        'payment_method_id',
        'transaction_id',
        'gateway_transaction_id',
        'payment_method',
        'amount',
        'currency',
        'qr_string',
        'qr_image_url',
        'deep_link',
        'status',
        'failure_reason',
        'raw_payload',
        'raw_callback',
        'verified_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'raw_payload' => 'array',
            'raw_callback' => 'array',
            'verified_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED || ($this->expires_at && $this->expires_at->isPast());
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            self::STATUS_PAID => ['label' => 'Paid', 'class' => 'bg-emerald-500/15 text-emerald-500 border-emerald-500/20'],
            self::STATUS_PENDING => ['label' => 'Pending', 'class' => 'bg-amber-500/15 text-amber-500 border-amber-500/20'],
            self::STATUS_FAILED => ['label' => 'Failed', 'class' => 'bg-rose-500/15 text-rose-500 border-rose-500/20'],
            self::STATUS_EXPIRED => ['label' => 'Expired', 'class' => 'bg-gray-500/15 text-gray-400 border-gray-500/20'],
            default => ['label' => ucfirst($this->status), 'class' => 'bg-gray-500/15 text-gray-400 border-gray-500/20'],
        };
    }
}
