<?php
// SSLCOMMERZ: create a session and redirect. Use sandbox credentials first.
$base = 'https://sandbox.sslcommerz.com'; // live: https://securepay.sslcommerz.com
$post = [
    'store_id'         => getenv('SSLC_STORE_ID'),
    'store_passwd'     => getenv('SSLC_STORE_PASSWD'),
    'total_amount'     => '100.00',
    'currency'         => 'BDT',
    'tran_id'          => uniqid('ORD'),
    'success_url'      => 'https://example.com/ssl/success',
    'fail_url'         => 'https://example.com/ssl/fail',
    'cancel_url'       => 'https://example.com/ssl/cancel',
    'ipn_url'          => 'https://example.com/ssl/ipn',
    'cus_name'         => 'Test User', 'cus_email' => 'test@example.com',
    'cus_add1'         => 'Dhaka', 'cus_city' => 'Dhaka', 'cus_country' => 'Bangladesh',
    'cus_phone'        => '01700000000',
    'shipping_method'  => 'NO', 'product_name' => 'Test', 'product_category' => 'General',
    'product_profile'  => 'general',
];
$ch = curl_init("$base/gwprocess/v4/api.php");
curl_setopt_array($ch, [CURLOPT_POST => true, CURLOPT_POSTFIELDS => http_build_query($post),
                        CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 30]);
$res = json_decode(curl_exec($ch), true);
if (!empty($res['GatewayPageURL'])) { header('Location: ' . $res['GatewayPageURL']); exit; }
http_response_code(502); echo 'Session failed: ' . ($res['failedreason'] ?? 'unknown');
