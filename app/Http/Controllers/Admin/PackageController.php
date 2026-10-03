<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Game;
use App\Models\GamePackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(Request $request): View
    {
        $query = GamePackage::with('game')->orderBy('sort_order');

        if ($gameId = $request->input('game_id')) {
            $query->where('game_id', $gameId);
        }

        $packages = $query->paginate(20)->withQueryString();
        $games = Game::all();

        return view('admin.packages.index', compact('packages', 'games'));
    }

    public function create(): View
    {
        $games = Game::all();
        return view('admin.packages.create', compact('games'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'game_id' => ['required', 'exists:games,id'],
            'name' => ['required', 'string', 'max:100'],
            'provider_code' => ['required', 'string', 'max:100'],
            'amount_value' => ['required', 'integer', 'min:1'],
            'bonus_value' => ['nullable', 'integer', 'min:0'],
            'price_khr' => ['required', 'numeric', 'min:0'],
            'price_usd' => ['required', 'numeric', 'min:0'],
            'original_price_khr' => ['nullable', 'numeric', 'min:0'],
            'badge' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['bonus_value'] = (int) ($validated['bonus_value'] ?? 0);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['is_active'] = $request->boolean('is_active', true);

        $package = GamePackage::create($validated);

        AuditLog::record('package.create', "Created package '{$package->name}' for {$package->game->name}");

        return redirect()->route('admin.packages.index', ['game_id' => $package->game_id])
            ->with('success', "Package '{$package->name}' created successfully.");
    }

    public function edit(int $id): View
    {
        $package = GamePackage::with('game')->findOrFail($id);
        $games = Game::all();
        return view('admin.packages.edit', compact('package', 'games'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $package = GamePackage::findOrFail($id);

        $validated = $request->validate([
            'game_id' => ['required', 'exists:games,id'],
            'name' => ['required', 'string', 'max:100'],
            'provider_code' => ['required', 'string', 'max:100'],
            'amount_value' => ['required', 'integer', 'min:1'],
            'bonus_value' => ['nullable', 'integer', 'min:0'],
            'price_khr' => ['required', 'numeric', 'min:0'],
            'price_usd' => ['required', 'numeric', 'min:0'],
            'original_price_khr' => ['nullable', 'numeric', 'min:0'],
            'badge' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['bonus_value'] = (int) ($validated['bonus_value'] ?? 0);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['is_active'] = $request->boolean('is_active');

        $package->update($validated);

        AuditLog::record('package.update', "Updated package '{$package->name}'");

        return redirect()->route('admin.packages.index', ['game_id' => $package->game_id])
            ->with('success', "Package '{$package->name}' updated successfully.");
    }

    public function destroy(int $id): RedirectResponse
    {
        $package = GamePackage::withCount('orders')->findOrFail($id);

        if ($package->orders_count > 0) {
            return back()->with('error', "Cannot delete package '{$package->name}' because orders were made for it. You can mark it as inactive instead.");
        }

        $name = $package->name;
        $gameId = $package->game_id;
        $package->delete();

        AuditLog::record('package.delete', "Deleted package '{$name}'");

        return redirect()->route('admin.packages.index', ['game_id' => $gameId])
            ->with('success', "Package '{$name}' deleted successfully.");
    }
}
