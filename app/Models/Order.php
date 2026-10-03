<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING_PAYMENT = 'pending_payment';
    public const STATUS_PAID = 'paid';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'order_number',
        'game_id',
        'game_package_id',
        'player_id',
        'zone_id',
        'player_nickname',
        'customer_phone',
        'customer_email',
        'customer_ip',
        'user_agent',
        'package_name',
        'unit_price',
        'discount_amount',
        'total_amount',
        'currency',
        'status',
        'notes',
        'paid_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(GamePackage::class, 'game_package_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function topupTransactions(): HasMany
    {
        return $this->hasMany(TopUpTransaction::class);
    }

    public function latestTopupTransaction(): HasOne
    {
        return $this->hasOne(TopUpTransaction::class)->latestOfMany();
    }

    public function isPaid(): bool
    {
        return in_array($this->status, [self::STATUS_PAID, self::STATUS_PROCESSING, self::STATUS_COMPLETED]);
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function getFormattedTotalAttribute(): string
    {
        if ($this->currency === 'USD') {
            return '$' . number_format($this->total_amount, 2);
        }
        return number_format($this->total_amount) . '៛';
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            self::STATUS_COMPLETED => ['label' => 'Completed', 'class' => 'bg-emerald-500/15 text-emerald-500 border-emerald-500/20'],
            self::STATUS_PROCESSING => ['label' => 'Processing', 'class' => 'bg-amber-500/15 text-amber-500 border-amber-500/20'],
            self::STATUS_PAID => ['label' => 'Paid', 'class' => 'bg-blue-500/15 text-blue-500 border-blue-500/20'],
            self::STATUS_PENDING_PAYMENT => ['label' => 'Pending Payment', 'class' => 'bg-yellow-500/15 text-yellow-500 border-yellow-500/20'],
            self::STATUS_FAILED => ['label' => 'Failed', 'class' => 'bg-rose-500/15 text-rose-500 border-rose-500/20'],
            self::STATUS_CANCELLED => ['label' => 'Cancelled', 'class' => 'bg-gray-500/15 text-gray-400 border-gray-500/20'],
            default => ['label' => ucfirst($this->status), 'class' => 'bg-gray-500/15 text-gray-400 border-gray-500/20'],
        };
    }
}
