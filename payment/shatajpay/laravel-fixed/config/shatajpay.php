<?php

// Copy to config/shatajpay.php and set the values in .env (never commit secrets).
return [
    'base_url'   => env('SHATAJPAY_BASE_URL', 'http://pay.shataj.com'), // try https://pay.shataj.com first
    'username'   => env('SHATAJPAY_USERNAME'),
    'password'   => env('SHATAJPAY_PASSWORD'),
    'app_key'    => env('SHATAJPAY_APP_KEY'),
    'app_secret' => env('SHATAJPAY_APP_SECRET'),
    'gateway_id' => env('SHATAJPAY_GATEWAY_ID'),

    // 'sandbox' or 'live'. The vendor sample sends header sandbox=0 for sandbox and 1 for live.
    'mode'       => env('SHATAJPAY_MODE', 'sandbox'),

    // The documented callback is an unsigned GET (status + orderId) and no verify API is documented.
    // true  = mark the order "completed" on status=Complete (vendor's flow).
    // false = mark it "awaiting_verification" so you can confirm it in the ShatajPay panel first.
    'trust_callback' => env('SHATAJPAY_TRUST_CALLBACK', true),

    // Where to send the customer after the callback.
    'success_url' => env('SHATAJPAY_SUCCESS_URL', '/'),
    'failed_url'  => env('SHATAJPAY_FAILED_URL', '/'),
];
