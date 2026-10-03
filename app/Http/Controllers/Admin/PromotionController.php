<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Promotion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PromotionController extends Controller
{
    public function index(): View
    {
        $promotions = Promotion::latest()->paginate(15);
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create(): View
    {
        return view('admin.promotions.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'code' => ['nullable', 'string', 'max:50', 'unique:promotions,code'],
            'discount_type' => ['required', 'in:percentage,fixed'],
            'discount_value' => ['required', 'numeric', 'min:0'],
            'min_spend' => ['nullable', 'numeric', 'min:0'],
            'max_discount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'banner' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        $validated['code'] = $validated['code'] ? strtoupper($validated['code']) : null;
        $validated['min_spend'] = (float) ($validated['min_spend'] ?? 0);
        $validated['is_active'] = $request->boolean('is_active', true);

        $promo = Promotion::create($validated);

        AuditLog::record('promotion.create', "Created promotion '{$promo->title}'");

        return redirect()->route('admin.promotions.index')->with('success', "Promotion '{$promo->title}' created successfully.");
    }

    public function edit(int $id): View
    {
        $promotion = Promotion::findOrFail($id);
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $promotion = Promotion::findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'code' => ['nullable', 'string', 'max:50', 'unique:promotions,code,' . $promotion->id],
            'discount_type' => ['required', 'in:percentage,fixed'],
            'discount_value' => ['required', 'numeric', 'min:0'],
            'min_spend' => ['nullable', 'numeric', 'min:0'],
            'max_discount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'banner' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        $validated['code'] = $validated['code'] ? strtoupper($validated['code']) : null;
        $validated['min_spend'] = (float) ($validated['min_spend'] ?? 0);
        $validated['is_active'] = $request->boolean('is_active');

        $promotion->update($validated);

        AuditLog::record('promotion.update', "Updated promotion '{$promotion->title}'");

        return redirect()->route('admin.promotions.index')->with('success', "Promotion '{$promotion->title}' updated successfully.");
    }

    public function destroy(int $id): RedirectResponse
    {
        $promotion = Promotion::findOrFail($id);
        $title = $promotion->title;
        $promotion->delete();

        AuditLog::record('promotion.delete', "Deleted promotion '{$title}'");

        return redirect()->route('admin.promotions.index')->with('success', "Promotion '{$title}' deleted successfully.");
    }
}
