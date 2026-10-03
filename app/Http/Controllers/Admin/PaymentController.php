<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Payment;
use App\Services\Payment\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $paymentService)
    {
    }

    public function index(Request $request): View
    {
        $query = Payment::with(['order.game', 'paymentMethod'])->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhere('gateway_transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('order', function ($oq) use ($search) {
                      $oq->where('order_number', 'like', "%{$search}%");
                  });
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $payments = $query->paginate(20)->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }

    public function manualVerify(int $id): RedirectResponse
    {
        $payment = Payment::findOrFail($id);

        $result = $this->paymentService->checkAndProcessPayment($payment);

        AuditLog::record('payment.manual_verify', "Admin triggered manual payment verification for Transaction {$payment->transaction_id}");

        if ($result['paid'] ?? false) {
            return back()->with('success', "Payment {$payment->transaction_id} verified as PAID! Automatic top-up dispatched.");
        }

        return back()->with('error', "Verification checked with ABA: Payment status remains '{$result['status']}'.");
    }
}
