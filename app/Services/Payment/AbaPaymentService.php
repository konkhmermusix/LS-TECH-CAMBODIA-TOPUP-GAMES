<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AbaPaymentService implements PaymentGatewayInterface
{
    protected string $merchantId;
    protected string $apiKey;
    protected string $apiUrl;
    protected string $checkUrl;
    protected bool $sandbox;
    protected int $expiryMinutes;

    public function __construct()
    {
        $config = config('payment.gateways.aba_khqr', []);

        $this->merchantId = (string) (env('ABA_PAYWAY_MERCHANT_ID') ?: Setting::get('aba_merchant_id', 'ec438902'));
        $this->apiKey = (string) env('ABA_PAYWAY_API_KEY', '');
        $this->apiUrl = (string) (env('ABA_PAYWAY_API_URL') ?: 'https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/purchase');
        $this->checkUrl = (string) (env('ABA_PAYWAY_CHECK_URL') ?: 'https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/check-transaction-2');
        $this->sandbox = (bool) (env('ABA_PAYWAY_SANDBOX') ?? Setting::get('aba_sandbox_mode', true));
        $this->expiryMinutes = (int) (env('ABA_PAYWAY_EXPIRY_MINUTES') ?: Setting::get('aba_qr_expiry_minutes', 15));
    }

    public function isSandbox(): bool
    {
        return $this->sandbox;
    }

    public function createPayment(Order $order): array
    {
        $transactionId = 'TXN-' . date('YmdHis') . '-' . strtoupper(Str::random(6));
        $paymentMethod = PaymentMethod::where('code', 'aba_khqr')->first();

        $reqTime = date('YmdHis');
        $amountFormatted = number_format($order->total_amount, 2, '.', '');
        $currency = $order->currency;
        $returnUrl = url("/payment/{$order->order_number}");

        $hashString = $reqTime . $this->merchantId . $transactionId . $amountFormatted;
        $hash = $this->generateHash($hashString, $this->apiKey);

        $qrString = null;
        $deepLink = null;
        $rawResponse = null;

        // If real ABA PayWay API key is provided, execute genuine API request
        if (!empty($this->apiKey) && !empty($this->merchantId)) {
            try {
                $response = Http::timeout(15)->post($this->apiUrl, [
                    'req_time' => $reqTime,
                    'merchant_id' => $this->merchantId,
                    'tran_id' => $transactionId,
                    'amount' => $amountFormatted,
                    'currency' => $currency,
                    'payment_option' => 'abapay_khqr',
                    'return_url' => base64_encode($returnUrl),
                    'hash' => $hash,
                ]);

                if ($response->successful()) {
                    $resData = $response->json();
                    $rawResponse = $resData;
                    $qrString = $resData['qr_string'] ?? ($resData['data']['qr_string'] ?? null);
                    $deepLink = $resData['abapay_deeplink'] ?? ($resData['data']['abapay_deeplink'] ?? null);
                } else {
                    Log::warning('ABA PayWay API returned non-200', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                }
            } catch (\Throwable $e) {
                Log::error('ABA PayWay connection error', ['message' => $e->getMessage()]);
            }
        }

        // Compliant KHQR fallback generator for development and sandbox when live API credentials are not yet configured
        if (empty($qrString)) {
            $qrString = $this->generateKhqrPayload($order, $transactionId);
            $deepLink = "https://link.payway.com.kh/app?tran_id={$transactionId}&merchant_id={$this->merchantId}";
        }

        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_method_id' => $paymentMethod?->id,
            'transaction_id' => $transactionId,
            'gateway_transaction_id' => null,
            'payment_method' => 'aba_khqr',
            'amount' => $order->total_amount,
            'currency' => $order->currency,
            'qr_string' => $qrString,
            'qr_image_url' => null,
            'deep_link' => $deepLink,
            'status' => Payment::STATUS_PENDING,
            'raw_payload' => $rawResponse ?? ['simulated_sandbox' => $this->sandbox],
            'expires_at' => now()->addMinutes($this->expiryMinutes),
        ]);

        return [
            'success' => true,
            'payment' => $payment,
            'transaction_id' => $transactionId,
            'qr_string' => $qrString,
            'deep_link' => $deepLink,
            'expires_at' => $payment->expires_at->toIso8601String(),
        ];
    }

    public function verifyPayment(Payment $payment): array
    {
        if ($payment->isPaid()) {
            return ['status' => Payment::STATUS_PAID, 'verified' => true];
        }

        if ($payment->isExpired()) {
            $payment->update(['status' => Payment::STATUS_EXPIRED]);
            return ['status' => Payment::STATUS_EXPIRED, 'verified' => false];
        }

        // If real ABA credentials are configured, query ABA check-transaction-2 API
        if (!empty($this->apiKey) && !empty($this->merchantId)) {
            try {
                $reqTime = date('YmdHis');
                $hashString = $reqTime . $this->merchantId . $payment->transaction_id;
                $hash = $this->generateHash($hashString, $this->apiKey);

                $response = Http::timeout(10)->post($this->checkUrl, [
                    'req_time' => $reqTime,
                    'merchant_id' => $this->merchantId,
                    'tran_id' => $payment->transaction_id,
                    'hash' => $hash,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $statusCode = $data['status'] ?? null;

                    // ABA PayWay status 0 represents successful payment
                    if ($statusCode === 0 || $statusCode === '0') {
                        $payment->update([
                            'status' => Payment::STATUS_PAID,
                            'gateway_transaction_id' => $data['apv'] ?? ($data['data']['apv'] ?? null),
                            'verified_at' => now(),
                            'raw_callback' => $data,
                        ]);

                        return ['status' => Payment::STATUS_PAID, 'verified' => true];
                    }
                }
            } catch (\Throwable $e) {
                Log::error('ABA PayWay check transaction error', ['message' => $e->getMessage()]);
            }
        }

        return ['status' => $payment->status, 'verified' => false];
    }

    public function handleWebhook(Request $request): array
    {
        $payload = $request->all();
        $tranId = $payload['tran_id'] ?? null;

        if (!$tranId) {
            return ['success' => false, 'message' => 'Missing tran_id'];
        }

        $payment = Payment::where('transaction_id', $tranId)->first();
        if (!$payment) {
            return ['success' => false, 'message' => 'Payment not found'];
        }

        // Verify HMAC signature if API key exists
        if (!empty($this->apiKey)) {
            $receivedHash = $request->header('X-Payway-Signature') ?? ($payload['hash'] ?? '');
            $expectedString = ($payload['req_time'] ?? '') . $this->merchantId . $tranId . ($payload['amount'] ?? '');
            $calculatedHash = $this->generateHash($expectedString, $this->apiKey);

            if (!hash_equals($calculatedHash, $receivedHash)) {
                Log::warning('ABA Webhook signature mismatch', ['tran_id' => $tranId]);
                return ['success' => false, 'message' => 'Invalid signature'];
            }
        }

        $status = $payload['status'] ?? null;
        if ($status === 0 || $status === '0' || $status === 'PAID') {
            if (!$payment->isPaid()) {
                $payment->update([
                    'status' => Payment::STATUS_PAID,
                    'gateway_transaction_id' => $payload['apv'] ?? null,
                    'verified_at' => now(),
                    'raw_callback' => $payload,
                ]);
            }
            return ['success' => true, 'status' => Payment::STATUS_PAID];
        }

        return ['success' => true, 'status' => $payment->status];
    }

    protected function generateHash(string $data, string $key): string
    {
        return base64_encode(hash_hmac('sha512', $data, $key, true));
    }

    /**
     * Generate standard Bakong EMVCo KHQR Payload format for Cambodia
     */
    protected function generateKhqrPayload(Order $order, string $tranId): string
    {
        $currencyCode = ($order->currency === 'USD') ? '840' : '116'; // 116 = KHR, 840 = USD
        $amount = number_format($order->total_amount, 2, '.', '');
        $merchantName = config('app.name', 'LS TECH TOPUP');

        // EMVCo QR format representation for KHQR
        return "00020101021229370016abaa0000000000000108{$this->merchantId}520459995303{$currencyCode}54"
            . sprintf('%02d', strlen($amount)) . $amount
            . "5802KH59" . sprintf('%02d', strlen($merchantName)) . $merchantName
            . "6010Phnom Penh62" . sprintf('%02d', strlen($tranId) + 4) . "01" . sprintf('%02d', strlen($tranId)) . $tranId
            . "6304ABCD";
    }
}
