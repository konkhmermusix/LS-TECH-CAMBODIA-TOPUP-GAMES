<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Order;
use App\Models\TopUpTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        // Sales Breakdown by Game
        $salesByGame = Game::select('games.id', 'games.name', 'games.logo')
            ->leftJoin('orders', 'games.id', '=', 'orders.game_id')
            ->selectRaw('COUNT(orders.id) as total_orders')
            ->selectRaw('SUM(CASE WHEN orders.status IN ("paid", "completed") THEN orders.total_amount ELSE 0 END) as total_revenue')
            ->groupBy('games.id', 'games.name', 'games.logo')
            ->get();

        // 7-Day Revenue Trend
        $sevenDaysRevenue = Order::select(
            DB::raw('DATE(created_at) as order_date'),
            DB::raw('COUNT(id) as orders_count'),
            DB::raw('SUM(CASE WHEN status IN ("paid", "completed") THEN total_amount ELSE 0 END) as revenue')
        )
        ->where('created_at', '>=', now()->subDays(7))
        ->groupBy('order_date')
        ->orderBy('order_date')
        ->get();

        // Top-Up Performance Breakdown
        $topupStats = TopUpTransaction::select(
            'status',
            DB::raw('COUNT(id) as count')
        )->groupBy('status')->pluck('count', 'status')->toArray();

        return view('admin.reports.index', compact('salesByGame', 'sevenDaysRevenue', 'topupStats'));
    }
}
