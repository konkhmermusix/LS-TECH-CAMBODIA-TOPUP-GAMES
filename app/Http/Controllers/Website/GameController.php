<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GameController extends Controller
{
    public function index(Request $request): View
    {
        $query = Game::where('is_active', true)->with('activePackages')->orderBy('sort_order');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('publisher', 'like', "%{$search}%");
            });
        }

        $games = $query->get();

        return view('website.games.index', compact('games'));
    }

    public function topup(string $slug): View
    {
        $game = Game::where('slug', $slug)
            ->where('is_active', true)
            ->with('activePackages')
            ->firstOrFail();

        $paymentMethods = PaymentMethod::where('is_active', true)->orderBy('sort_order')->get();

        return view('website.games.topup', compact('game', 'paymentMethods'));
    }
}
