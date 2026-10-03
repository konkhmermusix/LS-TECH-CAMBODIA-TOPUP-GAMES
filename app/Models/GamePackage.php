<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GamePackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'name',
        'provider_code',
        'amount_value',
        'bonus_value',
        'price_khr',
        'price_usd',
        'original_price_khr',
        'badge',
        'icon',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'amount_value' => 'integer',
            'bonus_value' => 'integer',
            'price_khr' => 'decimal:2',
            'price_usd' => 'decimal:2',
            'original_price_khr' => 'decimal:2',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getFormattedPriceKhrAttribute(): string
    {
        return number_format($this->price_khr) . '៛';
    }

    public function getFormattedPriceUsdAttribute(): string
    {
        return '$' . number_format($this->price_usd, 2);
    }
}
