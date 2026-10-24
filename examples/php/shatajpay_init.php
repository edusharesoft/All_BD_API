<?php
// ShatajPay: initialize a payment (based on vendor doc; confirm sandbox flag and https with Shataj).
$base = getenv('SHATAJPAY_BASE') ?: 'http://pay.shataj.com';
$payload = [
    'amount' => 1000, 'currency' => 'BDT',
    'name' => 'Customer Name', 'email' => 'customer@example.com', 'mobile' => '01700000000',
    'Address' => 'Dhaka, Bangladesh', 'opt_value_a' => '', 'opt_value_b' => '',
    'transactionId' => bin2hex(random_bytes(16)), 'orderId' => bin2hex(random_bytes(16)),
];
$ch = curl_init($base . '/api/v1/user/payment-system');
curl_setopt_array($ch, [
    CURLOPT_POST => true, CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 10,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json', 'Accept: application/json',
        'username: ' . getenv('SHATAJPAY_USERNAME'), 'password: ' . getenv('SHATAJPAY_PASSWORD'),
        'appkey: ' . getenv('SHATAJPAY_APPKEY'), 'appsecret: ' . getenv('SHATAJPAY_APPSECRET'),
        'gatewayid: ' . getenv('SHATAJPAY_GATEWAY_ID'),
        'sandbox: ' . (getenv('SHATAJPAY_SANDBOX') ?: '0'), // vendor sample: 0 = sandbox, 1 = live
    ],
    CURLOPT_POSTFIELDS => json_encode($payload),
]);
$res = json_decode(curl_exec($ch), true);
if (($res['status'] ?? false) === true && ($res['status_code'] ?? 0) === 200) {
    // save transactionId/orderId as "pending" first, then:
    header('Location: ' . $res['redirect_url']); exit;
}
http_response_code(502); echo $res['message'] ?? 'Payment initialization failed';
