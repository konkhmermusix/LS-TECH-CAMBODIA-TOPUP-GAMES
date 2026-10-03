<?php

namespace App\Services\TopUp;

use App\Models\Order;
use App\Models\Setting;
use App\Models\TopUpTransaction;
use App\Services\TopUp\Providers\AuthorizedApiTopUpProvider;
use App\Services\TopUp\Providers\MockTopUpProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TopUpService
{
    protected TopUpProviderInterface $provider;

    public function __construct()
    {
        $this->provider = $this->resolveProvider();
    }

    public function getProvider(): TopUpProviderInterface
    {
        return $this->provider;
    }

    protected function resolveProvider(): TopUpProviderInterface
    {
        $activeKey = Setting::get('topup_active_provider', config('topup.active_provider', 'mock_provider'));

        return match ($activeKey) {
            'authorized_api' => new AuthorizedApiTopUpProvider(),
            default => new MockTopUpProvider(),
        };
    }

    /**
     * Execute top-up for a verified paid order.
     */
    public function executeTopUp(Order $order): array
    {
        if ($order->status === Order::STATUS_COMPLETED) {
            return [
                'success' => true,
                'message' => 'Order is already marked as completed.',
                'order' => $order,
            ];
        }

        return DB::transaction(function () use ($order) {
            // Update order status to processing
            $order->update(['status' => Order::STATUS_PROCESSING]);

            $referenceId = 'TU-' . date('YmdHis') . '-' . strtoupper(Str::random(6));

            $transaction = TopUpTransaction::create([
                'order_id' => $order->id,
                'provider' => $this->provider->getProviderKey(),
                'reference_id' => $referenceId,
                'provider_order_id' => null,
                'player_id' => $order->player_id,
                'zone_id' => $order->zone_id,
                'package_code' => $order->package->provider_code,
                'status' => TopUpTransaction::STATUS_PROCESSING,
                'attempts' => 1,
                'executed_at' => now(),
            ]);

            // Dispatch to provider
            $result = $this->provider->topUp($order, $transaction);

            if ($result['success'] ?? false) {
                $transaction->update([
                    'status' => TopUpTransaction::STATUS_SUCCESS,
                    'provider_order_id' => $result['provider_order_id'] ?? null,
                    'response_payload' => $result['raw_response'] ?? null,
                    'completed_at' => now(),
                ]);

                $order->update([
                    'status' => Order::STATUS_COMPLETED,
                    'completed_at' => now(),
                ]);

                Log::info("Top-up SUCCESS for Order {$order->order_number}", [
                    'order_id' => $order->id,
                    'ref' => $referenceId,
                ]);

                return [
                    'success' => true,
                    'status' => 'completed',
                    'transaction' => $transaction,
                    'order' => $order->fresh(),
                ];
            } else {
                $transaction->update([
                    'status' => TopUpTransaction::STATUS_FAILED,
                    'provider_order_id' => $result['provider_order_id'] ?? null,
                    'error_code' => $result['error_code'] ?? 'PROVIDER_ERROR',
                    'error_message' => $result['error_message'] ?? 'Provider execution failed.',
                    'response_payload' => $result['raw_response'] ?? null,
                    'completed_at' => now(),
                ]);

                $order->update([
                    'status' => Order::STATUS_FAILED,
                ]);

                Log::error("Top-up FAILED for Order {$order->order_number}", [
                    'order_id' => $order->id,
                    'ref' => $referenceId,
                    'error' => $result['error_message'] ?? 'Unknown error',
                ]);

                return [
                    'success' => false,
                    'status' => 'failed',
                    'error' => $result['error_message'] ?? 'Top-up provider error',
                    'transaction' => $transaction,
                    'order' => $order->fresh(),
                ];
            }
        });
    }

    /**
     * Manually retry top-up for an order from admin dashboard.
     */
    public function retryTopUp(Order $order): array
    {
        return $this->executeTopUp($order);
    }

    /**
     * Fetch balance from active provider.
     */
    public function getBalance(): array
    {
        return $this->provider->balance();
    }
}
