<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Game;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GameController extends Controller
{
    public function index(): View
    {
        $games = Game::withCount(['packages', 'orders'])->orderBy('sort_order')->get();
        return view('admin.games.index', compact('games'));
    }

    public function create(): View
    {
        return view('admin.games.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:games,slug'],
            'publisher' => ['required', 'string', 'max:100'],
            'logo' => ['nullable', 'string', 'max:255'],
            'banner' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'instruction' => ['nullable', 'string'],
            'input_fields' => ['required', 'json'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['slug'] = $validated['slug'] ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        $validated['input_fields'] = json_decode($validated['input_fields'], true);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $request->input('sort_order', 0);

        $game = Game::create($validated);

        AuditLog::record('game.create', "Created game '{$game->name}'");

        return redirect()->route('admin.games.index')->with('success', "Game '{$game->name}' created successfully.");
    }

    public function edit(int $id): View
    {
        $game = Game::findOrFail($id);
        return view('admin.games.edit', compact('game'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $game = Game::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:100', 'unique:games,slug,' . $game->id],
            'publisher' => ['required', 'string', 'max:100'],
            'logo' => ['nullable', 'string', 'max:255'],
            'banner' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'instruction' => ['nullable', 'string'],
            'input_fields' => ['required', 'json'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['slug'] = Str::slug($validated['slug']);
        $validated['input_fields'] = json_decode($validated['input_fields'], true);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) $request->input('sort_order', 0);

        $game->update($validated);

        AuditLog::record('game.update', "Updated game '{$game->name}'");

        return redirect()->route('admin.games.index')->with('success', "Game '{$game->name}' updated successfully.");
    }

    public function destroy(int $id): RedirectResponse
    {
        $game = Game::withCount('orders')->findOrFail($id);

        if ($game->orders_count > 0) {
            return back()->with('error', "Cannot delete '{$game->name}' because it has existing orders. You may mark it as Inactive instead.");
        }

        $gameName = $game->name;
        $game->delete();

        AuditLog::record('game.delete', "Deleted game '{$gameName}'");

        return redirect()->route('admin.games.index')->with('success', "Game '{$gameName}' removed successfully.");
    }
}
