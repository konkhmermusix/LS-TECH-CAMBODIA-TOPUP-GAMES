<?php

return [
    'default' => env('PAYMENT_DEFAULT_GATEWAY', 'aba_khqr'),

    'gateways' => [
        'aba_khqr' => [
            'name' => 'ABA KHQR (PayWay)',
            'merchant_id' => env('ABA_PAYWAY_MERCHANT_ID', ''),
            'api_key' => env('ABA_PAYWAY_API_KEY', ''),
            'api_url' => env('ABA_PAYWAY_API_URL', 'https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/purchase'),
            'check_url' => env('ABA_PAYWAY_CHECK_URL', 'https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/check-transaction-2'),
            'sandbox' => env('ABA_PAYWAY_SANDBOX', true),
            'qr_expiry_minutes' => env('ABA_PAYWAY_EXPIRY_MINUTES', 15),
            'callback_url' => env('ABA_PAYWAY_CALLBACK_URL', null),
            'return_url' => env('ABA_PAYWAY_RETURN_URL', null),
        ],
    ],
];
