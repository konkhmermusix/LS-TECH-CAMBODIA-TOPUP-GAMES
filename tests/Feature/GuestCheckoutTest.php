<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\GamePackage;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Promotion;
use App\Services\Payment\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic dependencies if needed
        $this->seed(\Database\Seeders\GameSeeder::class);
        $this->seed(\Database\Seeders\GamePackageSeeder::class);
        $this->seed(\Database\Seeders\PaymentMethodSeeder::class);
        $this->seed(\Database\Seeders\SettingSeeder::class);
    }

    public function test_customer_can_initiate_guest_checkout(): void
    {
        $game = Game::where('slug', 'free-fire')->first();
        $package = $game->packages()->first();

        $response = $this->post(route('checkout.store'), [
            'game_id' => $game->id,
            'package_id' => $package->id,
            'player_id' => '3245770826',
        ]);

        $order = Order::where('player_id', '3245770826')->first();
        $this->assertNotNull($order);
        $this->assertEquals(Order::STATUS_PENDING_PAYMENT, $order->status);
        $this->assertEquals($package->price_khr, $order->total_amount);

        // Payment record was initialized
        $payment = $order->latestPayment;
        $this->assertNotNull($payment);
        $this->assertEquals(Payment::STATUS_PENDING, $payment->status);
        $this->assertNotEmpty($payment->qr_string);

        $response->assertRedirect(route('payment.show', $order->order_number));
    }

    public function test_server_calculates_price_strictly(): void
    {
        $game = Game::where('slug', 'free-fire')->first();
        $package = $game->packages()->first();

        // Attempting to inject manipulated fake price in request
        $response = $this->post(route('checkout.store'), [
            'game_id' => $game->id,
            'package_id' => $package->id,
            'player_id' => '3245770826',
            'price_khr' => 10, // malicious client input
            'total_amount' => 10,
        ]);

        $order = Order::where('player_id', '3245770826')->latest()->first();

        // Server MUST use DB package price, not client-provided price
        $this->assertEquals($package->price_khr, $order->total_amount);
    }

    public function test_promo_voucher_is_applied_correctly(): void
    {
        $promo = Promotion::create([
            'title' => 'Test Promo 1000 KHR Off',
            'code' => 'TEST1000',
            'discount_type' => 'fixed',
            'discount_value' => 1000,
            'min_spend' => 2000,
            'is_active' => true,
        ]);

        $game = Game::where('slug', 'free-fire')->first();
        $package = $game->packages()->where('price_khr', '>=', 4000)->first();

        $this->post(route('checkout.store'), [
            'game_id' => $game->id,
            'package_id' => $package->id,
            'player_id' => '3245770826',
            'promo_code' => 'TEST1000',
        ]);

        $order = Order::where('player_id', '3245770826')->latest()->first();
        $this->assertEquals(1000, $order->discount_amount);
        $this->assertEquals($package->price_khr - 1000, $order->total_amount);
    }

    public function test_payment_verification_triggers_automatic_topup(): void
    {
        $game = Game::where('slug', 'free-fire')->first();
        $package = $game->packages()->first();

        $this->post(route('checkout.store'), [
            'game_id' => $game->id,
            'package_id' => $package->id,
            'player_id' => '3245770826',
        ]);

        $order = Order::where('player_id', '3245770826')->latest()->first();
        $payment = $order->latestPayment;

        $paymentService = app(PaymentService::class);
        $paymentService->markAsPaidAndProcessTopUp($payment, ['simulated' => true]);

        $order->refresh();
        $payment->refresh();

        $this->assertEquals(Payment::STATUS_PAID, $payment->status);
        $this->assertEquals(Order::STATUS_COMPLETED, $order->status);
        $this->assertNotNull($order->completed_at);

        // TopUpTransaction created and completed
        $txn = $order->latestTopupTransaction;
        $this->assertNotNull($txn);
        $this->assertEquals('success', $txn->status);
    }
}
