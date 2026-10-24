<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\ShatajPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ShatajPayController extends Controller
{
    public function checkout(Request $request, ShatajPayService $shatajPay)
    {
        $customer = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['nullable', 'email', 'max:255'],
            'mobile'  => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        // TODO: compute from your cart/order on the server. Never trust an amount sent by the browser.
        $amount = 10.00;

        try {
            $transaction = $shatajPay->createTransaction($customer, $amount, [/* your own data */]);
            $redirectUrl = $shatajPay->initialize($transaction);
        } catch (Throwable $e) {
            Log::warning('ShatajPay initialize failed: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Payment could not be started. Please try again.');
        }

        // The payment page is on another domain, so use away().
        return redirect()->away($redirectUrl);
    }

    /**
     * Customer is redirected here by ShatajPay: GET ?status=Complete|Failed&orderId=...
     * Idempotent: only a "pending" transaction can change state.
     */
    public function callback(Request $request)
    {
        $status  = $request->query('status');
        $orderId = $request->query('orderId');

        if (!is_string($status) || !is_string($orderId) || $status === '' || $orderId === '') {
            return redirect()->to(config('shatajpay.failed_url'))->with('error', 'Invalid payment response.');
        }

        try {
            $transaction = DB::transaction(function () use ($status, $orderId) {
                $transaction = Transaction::where('order_id', $orderId)->lockForUpdate()->first();

                if (!$transaction || $transaction->status !== Transaction::PENDING) {
                    return $transaction; // unknown order, or already processed
                }

                if ($status === 'Complete') {
                    $transaction->status = config('shatajpay.trust_callback')
                        ? Transaction::COMPLETED
                        : Transaction::AWAITING_VERIFICATION;
                    $transaction->save();
                    // TODO: fulfil the order here (send email, create booking, ...) - inside this lock
                } elseif ($status === 'Failed') {
                    $transaction->status = Transaction::FAILED;
                    $transaction->save();
                }

                return $transaction;
            });
        } catch (Throwable $e) {
            Log::error('ShatajPay callback error: ' . $e->getMessage());

            return redirect()->to(config('shatajpay.failed_url'))->with('error', 'Could not process the payment response.');
        }

        if (!$transaction) {
            return redirect()->to(config('shatajpay.failed_url'))->with('error', 'Order not found.');
        }

        return match ($transaction->status) {
            Transaction::COMPLETED => redirect()->to(config('shatajpay.success_url'))->with('status', 'Payment completed.'),
            Transaction::AWAITING_VERIFICATION => redirect()->to(config('shatajpay.success_url'))->with('status', 'Payment received, awaiting confirmation.'),
            default => redirect()->to(config('shatajpay.failed_url'))->with('error', 'Payment failed or was cancelled.'),
        };
    }
}
