<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Order;
use App\Models\Payment;
use App\Models\TopUpTransaction;
use App\Services\TopUp\TopUpService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(protected TopUpService $topUpService)
    {
    }

    public function index(): View
    {
        $totalOrders = Order::count();
        $todayOrders = Order::whereDate('created_at', today())->count();
        $successfulOrders = Order::where('status', Order::STATUS_COMPLETED)->count();
        $pendingOrders = Order::whereIn('status', [Order::STATUS_PENDING_PAYMENT, Order::STATUS_PROCESSING])->count();
        $failedOrders = Order::where('status', Order::STATUS_FAILED)->count();

        // Revenue calculations (paid or completed)
        $totalRevenueKhr = Order::whereIn('status', [Order::STATUS_PAID, Order::STATUS_COMPLETED])
            ->where('currency', 'KHR')
            ->sum('total_amount');

        $todayRevenueKhr = Order::whereIn('status', [Order::STATUS_PAID, Order::STATUS_COMPLETED])
            ->where('currency', 'KHR')
            ->whereDate('paid_at', today())
            ->sum('total_amount');

        // Top-Up Success Rate %
        $totalTopups = TopUpTransaction::count();
        $successfulTopups = TopUpTransaction::where('status', TopUpTransaction::STATUS_SUCCESS)->count();
        $topupSuccessRate = $totalTopups > 0 ? round(($successfulTopups / $totalTopups) * 100, 1) : 100.0;

        // Recent items
        $recentOrders = Order::with(['game', 'package'])->latest()->take(6)->get();
        $recentTransactions = TopUpTransaction::with('order.game')->latest()->take(5)->get();
        $games = Game::withCount('orders')->get();

        // Provider balance check
        $providerBalance = $this->topUpService->getBalance();

        return view('admin.dashboard', compact(
            'totalOrders',
            'todayOrders',
            'successfulOrders',
            'pendingOrders',
            'failedOrders',
            'totalRevenueKhr',
            'todayRevenueKhr',
            'topupSuccessRate',
            'recentOrders',
            'recentTransactions',
            'games',
            'providerBalance'
        ));
    }
}
