<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TopUpTransaction;
use App\Services\TopUp\TopUpService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TopUpTransactionController extends Controller
{
    public function __construct(protected TopUpService $topUpService)
    {
    }

    public function index(Request $request): View
    {
        $query = TopUpTransaction::with('order.game')->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_id', 'like', "%{$search}%")
                  ->orWhere('provider_order_id', 'like', "%{$search}%")
                  ->orWhere('player_id', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $transactions = $query->paginate(20)->withQueryString();
        $providerBalance = $this->topUpService->getBalance();

        return view('admin.topups.index', compact('transactions', 'providerBalance'));
    }
}
