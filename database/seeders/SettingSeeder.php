<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['group' => 'general', 'key' => 'site_name', 'value' => 'LS Tech TopUp', 'type' => 'string', 'description' => 'Website Brand Name'],
            ['group' => 'general', 'key' => 'site_tagline', 'value' => 'Fast, Reliable Game Top-Up in Cambodia', 'type' => 'string', 'description' => 'Website Tagline'],
            ['group' => 'general', 'key' => 'telegram_support', 'value' => 'https://t.me/lstech_support', 'type' => 'string', 'description' => 'Telegram Customer Support Link'],
            ['group' => 'general', 'key' => 'contact_phone', 'value' => '+855 12 345 678', 'type' => 'string', 'description' => 'Customer Service Hotline'],
            ['group' => 'general', 'key' => 'currency_rate_usd_to_khr', 'value' => '4000', 'type' => 'integer', 'description' => 'Exchange rate: 1 USD to KHR'],

            // ABA Payway / KHQR Settings (defaults; real credentials come from .env)
            ['group' => 'aba', 'key' => 'aba_sandbox_mode', 'value' => 'true', 'type' => 'boolean', 'description' => 'Use ABA Payway Sandbox Environment'],
            ['group' => 'aba', 'key' => 'aba_merchant_id', 'value' => 'ec438902', 'type' => 'string', 'description' => 'ABA Merchant ID'],
            ['group' => 'aba', 'key' => 'aba_qr_expiry_minutes', 'value' => '15', 'type' => 'integer', 'description' => 'KHQR Code Expiry Duration in Minutes'],

            // Top-Up Provider Settings
            ['group' => 'topup', 'key' => 'topup_active_provider', 'value' => 'mock_provider', 'type' => 'string', 'description' => 'Active TopUp Provider (mock_provider, smile_one, lapakgaming, etc.)'],
            ['group' => 'topup', 'key' => 'topup_auto_process', 'value' => 'true', 'type' => 'boolean', 'description' => 'Automatically dispatch top-up when payment is verified'],
            ['group' => 'topup', 'key' => 'topup_max_retries', 'value' => '3', 'type' => 'integer', 'description' => 'Maximum automated retries for transient provider failures'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
