<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GamePackage;
use Illuminate\Database\Seeder;

class GamePackageSeeder extends Seeder
{
    public function run(): void
    {
        $freeFire = Game::where('slug', 'free-fire')->first();
        if ($freeFire) {
            $ffPackages = [
                [
                    'name' => '50 Diamonds',
                    'provider_code' => 'FF_50_DM',
                    'amount_value' => 50,
                    'bonus_value' => 0,
                    'price_khr' => 2000,
                    'price_usd' => 0.50,
                    'original_price_khr' => null,
                    'badge' => null,
                    'sort_order' => 1,
                ],
                [
                    'name' => '100 Diamonds',
                    'provider_code' => 'FF_100_DM',
                    'amount_value' => 100,
                    'bonus_value' => 10,
                    'price_khr' => 4000,
                    'price_usd' => 1.00,
                    'original_price_khr' => 4500,
                    'badge' => 'Popular',
                    'sort_order' => 2,
                ],
                [
                    'name' => '310 Diamonds',
                    'provider_code' => 'FF_310_DM',
                    'amount_value' => 310,
                    'bonus_value' => 30,
                    'price_khr' => 12000,
                    'price_usd' => 3.00,
                    'original_price_khr' => 13000,
                    'badge' => 'Hot',
                    'sort_order' => 3,
                ],
                [
                    'name' => '520 Diamonds',
                    'provider_code' => 'FF_520_DM',
                    'amount_value' => 520,
                    'bonus_value' => 60,
                    'price_khr' => 20000,
                    'price_usd' => 5.00,
                    'original_price_khr' => 22000,
                    'badge' => 'Best Value',
                    'sort_order' => 4,
                ],
                [
                    'name' => '1,060 Diamonds',
                    'provider_code' => 'FF_1060_DM',
                    'amount_value' => 1060,
                    'bonus_value' => 120,
                    'price_khr' => 40000,
                    'price_usd' => 10.00,
                    'original_price_khr' => 44000,
                    'badge' => 'Bonus +120',
                    'sort_order' => 5,
                ],
                [
                    'name' => '2,180 Diamonds',
                    'provider_code' => 'FF_2180_DM',
                    'amount_value' => 2180,
                    'bonus_value' => 250,
                    'price_khr' => 80000,
                    'price_usd' => 20.00,
                    'original_price_khr' => 88000,
                    'badge' => 'Mega Pack',
                    'sort_order' => 6,
                ],
            ];

            foreach ($ffPackages as $pkg) {
                GamePackage::updateOrCreate(
                    ['game_id' => $freeFire->id, 'provider_code' => $pkg['provider_code']],
                    array_merge($pkg, ['game_id' => $freeFire->id, 'is_active' => true])
                );
            }
        }

        $mlbb = Game::where('slug', 'mobile-legends')->first();
        if ($mlbb) {
            $mlPackages = [
                [
                    'name' => 'Weekly Diamond Pass',
                    'provider_code' => 'ML_WDP',
                    'amount_value' => 210,
                    'bonus_value' => 0,
                    'price_khr' => 8000,
                    'price_usd' => 2.00,
                    'original_price_khr' => 9000,
                    'badge' => 'Recommended',
                    'sort_order' => 1,
                ],
                [
                    'name' => '86 Diamonds',
                    'provider_code' => 'ML_86_DM',
                    'amount_value' => 78,
                    'bonus_value' => 8,
                    'price_khr' => 6000,
                    'price_usd' => 1.50,
                    'original_price_khr' => 6500,
                    'badge' => null,
                    'sort_order' => 2,
                ],
                [
                    'name' => '172 Diamonds',
                    'provider_code' => 'ML_172_DM',
                    'amount_value' => 156,
                    'bonus_value' => 16,
                    'price_khr' => 12000,
                    'price_usd' => 3.00,
                    'original_price_khr' => 13000,
                    'badge' => 'Popular',
                    'sort_order' => 3,
                ],
                [
                    'name' => '257 Diamonds',
                    'provider_code' => 'ML_257_DM',
                    'amount_value' => 234,
                    'bonus_value' => 23,
                    'price_khr' => 18000,
                    'price_usd' => 4.50,
                    'original_price_khr' => 19500,
                    'badge' => null,
                    'sort_order' => 4,
                ],
                [
                    'name' => '706 Diamonds',
                    'provider_code' => 'ML_706_DM',
                    'amount_value' => 625,
                    'bonus_value' => 81,
                    'price_khr' => 48000,
                    'price_usd' => 12.00,
                    'original_price_khr' => 52000,
                    'badge' => 'Best Value',
                    'sort_order' => 5,
                ],
                [
                    'name' => '2,195 Diamonds',
                    'provider_code' => 'ML_2195_DM',
                    'amount_value' => 1860,
                    'bonus_value' => 335,
                    'price_khr' => 145000,
                    'price_usd' => 36.00,
                    'original_price_khr' => 155000,
                    'badge' => 'Collector',
                    'sort_order' => 6,
                ],
            ];

            foreach ($mlPackages as $pkg) {
                GamePackage::updateOrCreate(
                    ['game_id' => $mlbb->id, 'provider_code' => $pkg['provider_code']],
                    array_merge($pkg, ['game_id' => $mlbb->id, 'is_active' => true])
                );
            }
        }
    }
}
