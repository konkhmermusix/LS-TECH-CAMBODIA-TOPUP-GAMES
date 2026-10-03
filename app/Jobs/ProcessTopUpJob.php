<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\TopUp\TopUpService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessTopUpJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 60;

    public function __construct(public Order $order)
    {
    }

    public function handle(TopUpService $topUpService): void
    {
        Log::info("ProcessTopUpJob executing for Order #{$this->order->order_number}");

        $result = $topUpService->executeTopUp($this->order);

        if (!($result['success'] ?? false)) {
            Log::warning("ProcessTopUpJob failed for Order #{$this->order->order_number}: " . ($result['error'] ?? ''));
        }
    }
}
