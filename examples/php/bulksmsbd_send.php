<?php
$q = http_build_query([
    'api_key' => getenv('BULKSMSBD_API_KEY'), 'type' => 'text',
    'number' => '8801700000000', 'senderid' => getenv('BULKSMSBD_SENDER_ID'),
    'message' => 'Your OTP is 123456']);
echo file_get_contents('https://bulksmsbd.net/api/smsapi?' . $q);
