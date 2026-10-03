<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::select(
            'player_id',
            DB::raw('MAX(game_id) as game_id'),
            DB::raw('MAX(customer_phone) as customer_phone'),
            DB::raw('MAX(player_nickname) as player_nickname'),
            DB::raw('COUNT(id) as total_orders'),
            DB::raw('SUM(CASE WHEN status IN ("paid", "completed") THEN total_amount ELSE 0 END) as total_spent'),
            DB::raw('MAX(created_at) as last_order_at')
        )->groupBy('player_id')->orderByDesc('last_order_at');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('player_id', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->paginate(20)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }
}
