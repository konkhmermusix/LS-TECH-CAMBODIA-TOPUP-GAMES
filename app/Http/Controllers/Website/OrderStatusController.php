<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderStatusController extends Controller
{
    public function show(string $orderNumber): View
    {
        $order = Order::with(['game', 'package', 'latestPayment', 'latestTopupTransaction'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('website.orders.show', compact('order'));
    }

    /**
     * AJAX Polling endpoint for real-time order tracking
     */
    public function status(string $orderNumber): JsonResponse
    {
        $order = Order::with(['latestPayment', 'latestTopupTransaction'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $topup = $order->latestTopupTransaction;
        $payment = $order->latestPayment;

        return response()->json([
            'order_number' => $order->order_number,
            'order_status' => $order->status,
            'payment_status' => $payment?->status ?? 'pending',
            'topup_status' => $topup?->status ?? 'pending',
            'is_completed' => $order->isCompleted(),
            'is_paid' => $order->isPaid(),
            'is_failed' => $order->isFailed(),
            'status_badge' => $order->status_badge,
            'completed_at' => $order->completed_at?->format('H:i:s'),
        ]);
    }

    /**
     * Public Order Tracking Lookup Page
     */
    public function track(Request $request): View
    {
        $order = null;
        $error = null;

        if ($query = $request->input('q')) {
            $query = trim($query);
            $order = Order::with(['game', 'package', 'latestPayment', 'latestTopupTransaction'])
                ->where('order_number', $query)
                ->orWhere('player_id', $query)
                ->latest()
                ->first();

            if (!$order) {
                $error = "No order found matching '{$query}'. Please check your Order Number or Player UID.";
            }
        }

        return view('website.orders.track', compact('order', 'error'));
    }
}
