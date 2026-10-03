<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $paymentService)
    {
    }

    public function show(string $orderNumber): View|RedirectResponse
    {
        $order = Order::with(['game', 'package', 'latestPayment'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        // If order is already fulfilled or completed, redirect to status page
        if ($order->isCompleted() || $order->isPaid()) {
            return redirect()->route('order.status', $order->order_number);
        }

        $payment = $order->latestPayment;

        // If no payment exists, create one
        if (!$payment) {
            $this->paymentService->initializePayment($order);
            $order->refresh();
            $payment = $order->latestPayment;
        }

        return view('website.payment', compact('order', 'payment'));
    }

    /**
     * Real-time polling endpoint for frontend AJAX payment check
     */
    public function checkStatus(string $orderNumber): JsonResponse
    {
        $order = Order::with('latestPayment')->where('order_number', $orderNumber)->firstOrFail();
        $payment = $order->latestPayment;

        if (!$payment) {
            return response()->json(['paid' => false, 'status' => 'pending']);
        }

        // Server-side payment check & auto top-up transition
        $result = $this->paymentService->checkAndProcessPayment($payment);

        $order->refresh();

        return response()->json([
            'paid' => $result['paid'],
            'payment_status' => $result['status'],
            'order_status' => $order->status,
            'redirect_url' => route('order.status', $order->order_number),
        ]);
    }

    /**
     * Developer/Sandbox payment simulator for testing the verified flow
     */
    public function simulateSandboxPayment(string $orderNumber): JsonResponse
    {
        $order = Order::with('latestPayment')->where('order_number', $orderNumber)->firstOrFail();
        $payment = $order->latestPayment;

        if (!$payment || $payment->isPaid()) {
            return response()->json(['success' => false, 'message' => 'Payment already processed or not found.']);
        }

        // Mark payment as paid server-side and trigger TopUpService
        $this->paymentService->markAsPaidAndProcessTopUp($payment, [
            'simulated' => true,
            'apv' => 'SIMULATED-' . time(),
            'paid_at' => now()->toIso8601String(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Simulated ABA KHQR payment verified successfully!',
            'redirect_url' => route('order.status', $order->order_number),
        ]);
    }
}
