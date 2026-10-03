<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Order;
use App\Models\Promotion;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $games = Game::where('is_active', true)
            ->with(['activePackages'])
            ->orderBy('sort_order')
            ->get();

        $promotions = Promotion::where('is_active', true)->take(3)->get();

        // Recent successful orders for social proof ticker (mask player id for privacy: e.g. 3245****26)
        $recentDeliveries = Order::where('status', Order::STATUS_COMPLETED)
            ->with('game')
            ->latest('completed_at')
            ->take(8)
            ->get()
            ->map(function ($order) {
                $pid = $order->player_id;
                $masked = strlen($pid) > 5 ? substr($pid, 0, 3) . '****' . substr($pid, -2) : '****';
                return [
                    'game' => $order->game->name,
                    'package' => $order->package_name,
                    'player' => $masked,
                    'time' => $order->completed_at ? $order->completed_at->diffForHumans() : 'Just now',
                ];
            });

        return view('website.home', compact('games', 'promotions', 'recentDeliveries'));
    }
}
