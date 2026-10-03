<?php

namespace App\Services\TopUp\Providers;

use App\Models\Order;
use App\Models\TopUpTransaction;
use App\Services\TopUp\TopUpProviderInterface;
use Illuminate\Support\Str;

class MockTopUpProvider implements TopUpProviderInterface
{
    public function getProviderKey(): string
    {
        return 'mock_provider';
    }

    public function topUp(Order $order, TopUpTransaction $transaction): array
    {
        // Simulate real response from authorized provider
        $providerOrderId = 'MOCK-ORD-' . strtoupper(Str::random(10));

        // If player UID is '9999999999' or contains 'fail', simulate failure for testing
        if ($order->player_id === '9999999999' || str_contains($order->player_id, 'fail')) {
            return [
                'success' => false,
                'status' => TopUpTransaction::STATUS_FAILED,
                'provider_order_id' => $providerOrderId,
                'error_code' => 'INVALID_PLAYER_ID',
                'error_message' => 'Player ID could not be found or verified by game server.',
                'raw_response' => [
                    'code' => 404,
                    'message' => 'Player not found',
                    'timestamp' => now()->toIso8601String(),
                ],
            ];
        }

        return [
            'success' => true,
            'status' => TopUpTransaction::STATUS_SUCCESS,
            'provider_order_id' => $providerOrderId,
            'error_code' => null,
            'error_message' => null,
            'raw_response' => [
                'code' => 200,
                'message' => 'Top-Up processed successfully',
                'diamonds_credited' => $order->package->amount_value + $order->package->bonus_value,
                'reference' => $transaction->reference_id,
                'timestamp' => now()->toIso8601String(),
            ],
        ];
    }

    public function checkStatus(TopUpTransaction $transaction): array
    {
        return [
            'status' => $transaction->status,
            'provider_order_id' => $transaction->provider_order_id,
            'message' => 'Simulated order status retrieved',
        ];
    }

    public function balance(): array
    {
        return [
            'success' => true,
            'currency' => 'USD',
            'balance' => 999999.00,
            'provider' => 'mock_provider',
        ];
    }
}
