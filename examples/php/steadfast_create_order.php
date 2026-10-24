<?php
$ch = curl_init('https://portal.packzy.com/api/v1/create_order');
curl_setopt_array($ch, [
    CURLOPT_POST => true, CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTPHEADER => ['Api-Key: ' . getenv('STEADFAST_API_KEY'),
                           'Secret-Key: ' . getenv('STEADFAST_SECRET_KEY'),
                           'Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode([
        'invoice' => 'ORD-1001', 'recipient_name' => 'Test Customer',
        'recipient_phone' => '01700000000', 'recipient_address' => 'House 1, Dhaka',
        'cod_amount' => 1060, 'note' => '']),
]);
echo curl_exec($ch);
