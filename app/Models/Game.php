<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'publisher',
        'logo',
        'banner',
        'description',
        'instruction',
        'input_fields',
        'validation_endpoint',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'input_fields' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function packages(): HasMany
    {
        return $this->hasMany(GamePackage::class)->orderBy('sort_order');
    }

    public function activePackages(): HasMany
    {
        return $this->hasMany(GamePackage::class)->where('is_active', true)->orderBy('sort_order');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
