<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GameController as AdminGameController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\TopUpTransactionController as AdminTopUpTransactionController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Website\CheckoutController;
use App\Http\Controllers\Website\GameController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\OrderStatusController;
use App\Http\Controllers\Website\PageController;
use App\Http\Controllers\Website\PaymentController;
use App\Http\Controllers\Website\PaymentWebhookController;
use App\Http\Controllers\Website\PlayerVerificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Customer Website Routes (Guest Checkout — No Customer Login Required)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/games', [GameController::class, 'index'])->name('games.index');
Route::get('/topup/{slug}', [GameController::class, 'topup'])->name('topup.show');

// Real-Time Player UID / Zone Verification API
Route::post('/api/player/verify', [PlayerVerificationController::class, 'verify'])->name('api.player.verify');

// Guest Checkout
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

// ABA KHQR Payment & Verification
Route::get('/payment/{orderNumber}', [PaymentController::class, 'show'])->name('payment.show');
Route::get('/payment/{orderNumber}/status', [PaymentController::class, 'checkStatus'])->name('payment.status');
Route::post('/payment/{orderNumber}/simulate', [PaymentController::class, 'simulateSandboxPayment'])->name('payment.simulate');

// ABA PayWay Server-to-Server Webhook
Route::post('/webhook/payment/aba', [PaymentWebhookController::class, 'aba'])->name('webhook.payment.aba');

// Order Tracking & Receipt
Route::get('/orders/track', [OrderStatusController::class, 'track'])->name('orders.track');
Route::get('/order/{orderNumber}', [OrderStatusController::class, 'show'])->name('order.status');
Route::get('/order/{orderNumber}/status', [OrderStatusController::class, 'status'])->name('order.status.poll');

// Static Help & FAQ
Route::get('/faq', [PageController::class, 'faq'])->name('faq');

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Protected Admin Routes (Requires admin middleware)
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index']);
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Orders
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/retry-topup', [AdminOrderController::class, 'retryTopUp'])->name('orders.retry');
        Route::post('/orders/{id}/cancel', [AdminOrderController::class, 'cancel'])->name('orders.cancel');

        // Games CRUD
        Route::resource('games', AdminGameController::class);

        // Packages CRUD
        Route::resource('packages', AdminPackageController::class);

        // Payments
        Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
        Route::post('/payments/{id}/verify', [AdminPaymentController::class, 'manualVerify'])->name('payments.verify');

        // Top-Up Provider Logs
        Route::get('/topups', [AdminTopUpTransactionController::class, 'index'])->name('topups.index');

        // Buyers / Customers
        Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');

        // Promotions CRUD
        Route::resource('promotions', AdminPromotionController::class);

        // Reports
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');

        // Notifications
        Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [AdminNotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [AdminNotificationController::class, 'markAllAsRead'])->name('notifications.read_all');

        // Admin Users CRUD
        Route::resource('users', AdminUserController::class);

        // System Settings
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

        // Profile
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    });
});
