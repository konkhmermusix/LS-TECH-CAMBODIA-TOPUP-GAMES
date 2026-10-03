<?php

namespace App\Services\TopUp\Providers;

use App\Models\Order;
use App\Models\TopUpTransaction;
use App\Services\TopUp\TopUpProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthorizedApiTopUpProvider implements TopUpProviderInterface
{
    protected string $apiUrl;
    protected string $merchantId;
    protected string $apiKey;
    protected string $apiSecret;

    public function __construct()
    {
        $config = config('topup.providers.authorized_api', []);
        $this->apiUrl = (string) ($config['api_url'] ?? env('TOPUP_PROVIDER_API_URL', ''));
        $this->merchantId = (string) ($config['merchant_id'] ?? env('TOPUP_PROVIDER_MERCHANT_ID', ''));
        $this->apiKey = (string) ($config['api_key'] ?? env('TOPUP_PROVIDER_API_KEY', ''));
        $this->apiSecret = (string) ($config['api_secret'] ?? env('TOPUP_PROVIDER_SECRET', ''));
    }

    public function getProviderKey(): string
    {
        return 'authorized_api';
    }

    public function topUp(Order $order, TopUpTransaction $transaction): array
    {
        if (empty($this->apiKey) || empty($this->apiUrl)) {
            Log::warning('Authorized Top-Up API credentials not configured');
            return [
                'success' => false,
                'status' => TopUpTransaction::STATUS_FAILED,
                'provider_order_id' => null,
                'error_code' => 'CREDENTIALS_MISSING',
                'error_message' => 'Provider API credentials not configured in environment.',
                'raw_response' => null,
            ];
        }

        try {
            $payload = [
                'merchant_id' => $this->merchantId,
                'reference_id' => $transaction->reference_id,
                'game_slug' => $order->game->slug,
                'package_code' => $transaction->package_code,
                'player_id' => $transaction->player_id,
                'zone_id' => $transaction->zone_id,
                'signature' => hash_hmac('sha256', $this->merchantId . $transaction->reference_id . $transaction->player_id, $this->apiSecret),
            ];

            $response = Http::timeout(25)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->post("{$this->apiUrl}/orders/topup", $payload);

            $data = $response->json();

            if ($response->successful() && ($data['success'] ?? false)) {
                return [
                    'success' => true,
                    'status' => TopUpTransaction::STATUS_SUCCESS,
                    'provider_order_id' => $data['order_id'] ?? null,
                    'error_code' => null,
                    'error_message' => null,
                    'raw_response' => $data,
                ];
            }

            return [
                'success' => false,
                'status' => TopUpTransaction::STATUS_FAILED,
                'provider_order_id' => $data['order_id'] ?? null,
                'error_code' => $data['error_code'] ?? 'PROVIDER_ERROR',
                'error_message' => $data['message'] ?? 'Provider failed to execute top-up order.',
                'raw_response' => $data,
            ];
        } catch (\Throwable $e) {
            Log::error('Authorized TopUp Provider connection error', ['message' => $e->getMessage()]);
            return [
                'success' => false,
                'status' => TopUpTransaction::STATUS_FAILED,
                'provider_order_id' => null,
                'error_code' => 'NETWORK_TIMEOUT',
                'error_message' => 'Failed to reach external provider service.',
                'raw_response' => ['exception' => $e->getMessage()],
            ];
        }
    }

    public function checkStatus(TopUpTransaction $transaction): array
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])
                ->get("{$this->apiUrl}/orders/{$transaction->reference_id}/status");

            $data = $response->json();
            return [
                'status' => $data['status'] ?? $transaction->status,
                'provider_order_id' => $data['order_id'] ?? $transaction->provider_order_id,
                'raw' => $data,
            ];
        } catch (\Throwable $e) {
            return ['status' => $transaction->status, 'error' => $e->getMessage()];
        }
    }

    public function balance(): array
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])
                ->get("{$this->apiUrl}/merchant/balance");

            $data = $response->json();
            return [
                'success' => true,
                'currency' => $data['currency'] ?? 'USD',
                'balance' => $data['balance'] ?? 0.00,
                'provider' => 'authorized_api',
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
