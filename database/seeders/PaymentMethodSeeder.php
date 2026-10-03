<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        PaymentMethod::updateOrCreate(
            ['code' => 'aba_khqr'],
            [
                'name' => 'ABA KHQR (PayWay)',
                'provider' => 'aba',
                'logo' => 'https://www.ababank.com/typo3conf/ext/aba_template/Resources/Public/Images/aba-logo.svg',
                'description' => 'Scan with ABA Mobile or any Bakong-enabled banking app in Cambodia.',
                'is_active' => true,
                'fee_percentage' => 0.00,
                'fee_fixed' => 0.00,
                'instructions' => 'Open your ABA Mobile or any banking app, tap QR Scan, and scan the QR code to complete payment instantly.',
                'sort_order' => 1,
            ]
        );
    }
}
