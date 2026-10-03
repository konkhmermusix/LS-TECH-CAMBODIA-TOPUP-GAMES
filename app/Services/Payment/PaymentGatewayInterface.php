<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    /**
     * Create payment transaction and generate KHQR payload/deep link.
     */
    public function createPayment(Order $order): array;

    /**
     * Server-side verify payment with gateway.
     */
    public function verifyPayment(Payment $payment): array;

    /**
     * Handle incoming gateway webhook.
     */
    public function handleWebhook(Request $request): array;

    /**
     * Check if gateway is in sandbox mode.
     */
    public function isSandbox(): bool;
}
