<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TopUpTransaction extends Model
{
    use HasFactory;

    protected $table = 'topup_transactions';

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'order_id',
        'provider',
        'reference_id',
        'provider_order_id',
        'player_id',
        'zone_id',
        'package_code',
        'status',
        'error_code',
        'error_message',
        'attempts',
        'request_payload',
        'response_payload',
        'executed_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'attempts' => 'integer',
            'request_payload' => 'array',
            'response_payload' => 'array',
            'executed_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isSuccess(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            self::STATUS_SUCCESS => ['label' => 'Success', 'class' => 'bg-emerald-500/15 text-emerald-500 border-emerald-500/20'],
            self::STATUS_PROCESSING => ['label' => 'Processing', 'class' => 'bg-amber-500/15 text-amber-500 border-amber-500/20'],
            self::STATUS_PENDING => ['label' => 'Pending', 'class' => 'bg-blue-500/15 text-blue-500 border-blue-500/20'],
            self::STATUS_FAILED => ['label' => 'Failed', 'class' => 'bg-rose-500/15 text-rose-500 border-rose-500/20'],
            default => ['label' => ucfirst($this->status), 'class' => 'bg-gray-500/15 text-gray-400 border-gray-500/20'],
        };
    }
}
