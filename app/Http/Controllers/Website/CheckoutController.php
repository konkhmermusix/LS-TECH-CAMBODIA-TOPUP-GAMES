<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GamePackage;
use App\Models\Order;
use App\Models\Promotion;
use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct(protected PaymentService $paymentService)
    {
    }

    /**
     * Process Guest Checkout and create Order + Payment
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'game_id' => ['required', 'exists:games,id'],
            'package_id' => ['required', 'exists:game_packages,id'],
            'player_id' => ['required', 'string', 'max:50'],
            'zone_id' => ['nullable', 'string', 'max:20'],
            'player_nickname' => ['nullable', 'string', 'max:100'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'customer_email' => ['nullable', 'email', 'max:100'],
            'promo_code' => ['nullable', 'string', 'max:50'],
        ]);

        $game = Game::where('id', $validated['game_id'])->where('is_active', true)->firstOrFail();
        $package = GamePackage::where('id', $validated['package_id'])
            ->where('game_id', $game->id)
            ->where('is_active', true)
            ->firstOrFail();

        // Strict Server Price Calculation
        $unitPrice = (float) $package->price_khr;
        $discountAmount = 0.00;

        // Apply promo code if provided
        if (!empty($validated['promo_code'])) {
            $promo = Promotion::where('code', strtoupper(trim($validated['promo_code'])))->first();
            if ($promo && $promo->isValidForAmount($unitPrice)) {
                $discountAmount = $promo->calculateDiscount($unitPrice);
                $promo->increment('usage_count');
            }
        }

        $totalAmount = max(0, $unitPrice - $discountAmount);

        // Generate Unique Order Number: TOP-YYYYMMDD-XXXXXX
        $orderNumber = 'TOP-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        // Create Order and Payment atomically
        $order = DB::transaction(function () use ($validated, $game, $package, $unitPrice, $discountAmount, $totalAmount, $orderNumber, $request) {
            $order = Order::create([
                'order_number' => $orderNumber,
                'game_id' => $game->id,
                'game_package_id' => $package->id,
                'player_id' => trim($validated['player_id']),
                'zone_id' => !empty($validated['zone_id']) ? trim($validated['zone_id']) : null,
                'player_nickname' => $validated['player_nickname'] ?? null,
                'customer_phone' => $validated['customer_phone'] ?? null,
                'customer_email' => $validated['customer_email'] ?? null,
                'customer_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'package_name' => $package->name,
                'unit_price' => $unitPrice,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'currency' => 'KHR',
                'status' => Order::STATUS_PENDING_PAYMENT,
            ]);

            // Initialize ABA KHQR payment
            $this->paymentService->initializePayment($order);

            return $order;
        });

        $paymentUrl = route('payment.show', $order->order_number);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'order_number' => $order->order_number,
                'redirect_url' => $paymentUrl,
            ]);
        }

        return redirect()->to($paymentUrl);
    }
}
