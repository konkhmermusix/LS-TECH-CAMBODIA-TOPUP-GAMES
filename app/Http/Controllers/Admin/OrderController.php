<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Game;
use App\Models\Order;
use App\Services\TopUp\TopUpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(protected TopUpService $topUpService)
    {
    }

    public function index(Request $request): View
    {
        $query = Order::with(['game', 'package', 'latestPayment', 'latestTopupTransaction'])->latest();

        // Search by Order Number or Player ID
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('player_id', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        // Filter by Game
        if ($gameId = $request->input('game_id')) {
            $query->where('game_id', $gameId);
        }

        // Filter by Status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Date Filter
        if ($date = $request->input('date')) {
            $query->whereDate('created_at', $date);
        }

        $orders = $query->paginate(15)->withQueryString();
        $games = Game::all();

        return view('admin.orders.index', compact('orders', 'games'));
    }

    public function show(int $id): View
    {
        $order = Order::with(['game', 'package', 'payments.paymentMethod', 'topupTransactions'])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function retryTopUp(int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);

        if (!$order->isPaid()) {
            return back()->with('error', 'Cannot trigger top-up because order payment has not been confirmed.');
        }

        AuditLog::record('order.retry_topup', "Admin triggered manual top-up retry for Order #{$order->order_number}");

        $result = $this->topUpService->retryTopUp($order);

        if ($result['success'] ?? false) {
            return back()->with('success', "Top-up successfully delivered to Player {$order->player_id}!");
        }

        return back()->with('error', 'Top-up retry failed: ' . ($result['error'] ?? 'Provider error'));
    }

    public function cancel(int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);

        if ($order->isCompleted()) {
            return back()->with('error', 'Cannot cancel an order that is already completed.');
        }

        $order->update(['status' => Order::STATUS_CANCELLED]);

        AuditLog::record('order.cancel', "Admin cancelled Order #{$order->order_number}");

        return back()->with('success', "Order #{$order->order_number} has been cancelled.");
    }
}
