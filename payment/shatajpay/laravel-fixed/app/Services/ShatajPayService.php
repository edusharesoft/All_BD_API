<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class ShatajPayService
{
    /**
     * Store the transaction BEFORE calling the gateway; the callback finds it by order_id.
     * $amount must be calculated on your server (cart/order), never taken from the browser.
     */
    public function createTransaction(array $customer, float $amount, array $meta = []): Transaction
    {
        return Transaction::create([
            'name'           => $customer['name'],
            'email'          => $customer['email'] ?? null,
            'phone'          => $customer['mobile'],
            'address'        => $customer['address'] ?? null,
            'method'         => 'shatajPay',
            'amount'         => $amount,
            'currency'       => 'BDT',
            'status'         => Transaction::INITIALIZE,
            'transaction_id' => (string) Str::uuid(),
            'order_id'       => (string) Str::uuid(),
            'meta'           => $meta ?: null,
        ]);
    }

    /**
     * Ask ShatajPay for a payment URL. Returns the redirect URL or throws RuntimeException.
     */
    public function initialize(Transaction $transaction): string
    {
        $cfg = config('shatajpay');

        foreach (['username', 'password', 'app_key', 'app_secret', 'gateway_id'] as $key) {
            if (empty($cfg[$key])) {
                throw new RuntimeException("ShatajPay config '{$key}' is missing.");
            }
        }

        $url = rtrim($cfg['base_url'], '/') . '/api/v1/user/payment-system';

        $response = Http::timeout(15)
            ->acceptJson()
            ->withHeaders([
                'username'  => $cfg['username'],
                'password'  => $cfg['password'],
                'appkey'    => $cfg['app_key'],
                'appsecret' => $cfg['app_secret'],
                'gatewayid' => $cfg['gateway_id'],
                // vendor sample: 0 = sandbox, 1 = live
                'sandbox'   => $cfg['mode'] === 'sandbox' ? 0 : 1,
            ])
            ->post($url, [
                'amount'        => (float) $transaction->amount,
                'currency'      => $transaction->currency,
                'name'          => $transaction->name,
                'email'         => $transaction->email ?? '',
                'mobile'        => $transaction->phone,
                'Address'       => $transaction->address ?? '',
                'opt_value_a'   => '',
                'opt_value_b'   => '',
                'transactionId' => $transaction->transaction_id,
                'orderId'       => $transaction->order_id,
            ]);

        $redirectUrl = $response->json('redirect_url');

        if (
            $response->successful()
            && $response->json('status') === true
            && (int) $response->json('status_code') === 200
            && is_string($redirectUrl) && $redirectUrl !== ''
        ) {
            // Request accepted: the customer is about to pay. This is NOT a paid order yet.
            $transaction->update(['status' => Transaction::PENDING]);

            return $redirectUrl;
        }

        $transaction->update(['status' => Transaction::FAILED]);

        throw new RuntimeException($response->json('message') ?: 'ShatajPay rejected the payment request.');
    }
}
