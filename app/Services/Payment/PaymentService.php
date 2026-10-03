<?php

namespace App\Services\Payment;

use App\Jobs\ProcessTopUpJob;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Setting;
use App\Services\TopUp\TopUpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct(
        protected AbaPaymentService $abaService,
        protected TopUpService $topUpService
    ) {
    }

    /**
     * Initialize payment for an order.
     */
    public function initializePayment(Order $order): array
    {
        return $this->abaService->createPayment($order);
    }

    /**
     * Check status of payment and trigger automatic top-up if paid.
     */
    public function checkAndProcessPayment(Payment $payment): array
    {
        if ($payment->isPaid()) {
            return [
                'status' => Payment::STATUS_PAID,
                'order_status' => $payment->order->status,
                'paid' => true,
            ];
        }

        // Verify with ABA Gateway
        $verification = $this->abaService->verifyPayment($payment);

        if ($verification['verified'] ?? false) {
            $this->markAsPaidAndProcessTopUp($payment);

            return [
                'status' => Payment::STATUS_PAID,
                'order_status' => $payment->order->fresh()->status,
                'paid' => true,
            ];
        }

        return [
            'status' => $payment->status,
            'order_status' => $payment->order->status,
            'paid' => false,
        ];
    }

    /**
     * Server-side verified: Mark payment and order as PAID, then immediately dispatch Top-Up.
     */
    public function markAsPaidAndProcessTopUp(Payment $payment, ?array $gatewayData = null): void
    {
        DB::transaction(function () use ($payment, $gatewayData) {
            $order = $payment->order()->lockForUpdate()->first();

            // Guard against duplicate processing
            if ($order->isPaid()) {
                return;
            }

            $payment->update([
                'status' => Payment::STATUS_PAID,
                'verified_at' => now(),
                'raw_callback' => $gatewayData ?? $payment->raw_callback,
            ]);

            $order->update([
                'status' => Order::STATUS_PAID,
                'paid_at' => now(),
            ]);

            Log::info("Payment verified as PAID for Order #{$order->order_number}");

            // Automatically trigger top-up if auto_process is enabled
            if (Setting::get('topup_auto_process', true)) {
                // If queue is synchronous or database, execute TopUp
                $this->topUpService->executeTopUp($order);
            }
        });
    }

    /**
     * Process ABA KHQR Webhook
     */
    public function handleAbaWebhook(Request $request): array
    {
        $result = $this->abaService->handleWebhook($request);

        if (($result['success'] ?? false) && ($result['status'] ?? '') === Payment::STATUS_PAID) {
            $tranId = $request->input('tran_id');
            $payment = Payment::where('transaction_id', $tranId)->first();

            if ($payment && !$payment->order->isPaid()) {
                $this->markAsPaidAndProcessTopUp($payment, $request->all());
            }
        }

        return $result;
    }
}
