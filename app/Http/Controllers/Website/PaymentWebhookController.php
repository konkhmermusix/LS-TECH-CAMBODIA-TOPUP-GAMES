<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function __construct(protected PaymentService $paymentService)
    {
    }

    /**
     * Handle ABA PayWay Server-to-Server Webhook
     */
    public function aba(Request $request): JsonResponse
    {
        Log::info('ABA PayWay Webhook Received', [
            'payload' => $request->all(),
            'ip' => $request->ip(),
        ]);

        $result = $this->paymentService->handleAbaWebhook($request);

        if ($result['success'] ?? false) {
            return response()->json([
                'status' => 'success',
                'message' => 'Webhook processed successfully.',
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => $result['message'] ?? 'Failed to process webhook.',
        ], 400);
    }
}
