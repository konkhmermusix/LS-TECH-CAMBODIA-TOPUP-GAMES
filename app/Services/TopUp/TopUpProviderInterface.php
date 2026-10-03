<?php

namespace App\Services\TopUp;

use App\Models\Order;
use App\Models\TopUpTransaction;

interface TopUpProviderInterface
{
    /**
     * Dispatch top-up to authorized external provider.
     */
    public function topUp(Order $order, TopUpTransaction $transaction): array;

    /**
     * Check status of a dispatched top-up order with provider.
     */
    public function checkStatus(TopUpTransaction $transaction): array;

    /**
     * Retrieve provider wallet balance / quota.
     */
    public function balance(): array;

    /**
     * Get unique provider identifier key.
     */
    public function getProviderKey(): string;
}
