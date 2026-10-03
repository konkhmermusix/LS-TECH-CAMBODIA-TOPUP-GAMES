<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        Promotion::updateOrCreate(
            ['code' => 'KHMERNEWYEAR'],
            [
                'title' => 'Khmer New Year Diamond Bonus — 10% Off',
                'banner' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=1200&auto=format&fit=crop&q=80',
                'discount_type' => 'percentage',
                'discount_value' => 10.00,
                'min_spend' => 10000,
                'max_discount' => 4000,
                'is_active' => true,
                'usage_limit' => 1000,
                'usage_count' => 12,
                'starts_at' => now()->subDays(5),
                'ends_at' => now()->addDays(25),
            ]
        );

        Promotion::updateOrCreate(
            ['code' => 'LSTECH1000'],
            [
                'title' => 'First Order Discount 1,000៛ Off',
                'banner' => null,
                'discount_type' => 'fixed',
                'discount_value' => 1000.00,
                'min_spend' => 8000,
                'max_discount' => 1000,
                'is_active' => true,
                'usage_limit' => 500,
                'usage_count' => 45,
                'starts_at' => now()->subDays(10),
                'ends_at' => now()->addDays(60),
            ]
        );
    }
}
