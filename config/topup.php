<?php

return [
    'active_provider' => env('TOPUP_ACTIVE_PROVIDER', 'mock_provider'),

    'providers' => [
        'mock_provider' => [
            'name' => 'Sandbox Simulated Provider',
            'auto_success' => env('TOPUP_MOCK_AUTO_SUCCESS', true),
        ],

        'authorized_api' => [
            'name' => 'Authorized Top-Up Partner API',
            'api_url' => env('TOPUP_PROVIDER_API_URL', 'https://api.partner-topup.com/v1'),
            'merchant_id' => env('TOPUP_PROVIDER_MERCHANT_ID', ''),
            'api_key' => env('TOPUP_PROVIDER_API_KEY', ''),
            'api_secret' => env('TOPUP_PROVIDER_SECRET', ''),
        ],
    ],
];
