<?php
// Call from your IPN / success handler. Never trust the redirect alone.
$base = 'https://sandbox.sslcommerz.com'; // live: https://securepay.sslcommerz.com
$val_id = $_POST['val_id'] ?? '';
$url = "$base/validator/api/validationserverAPI.php?" . http_build_query([
    'val_id' => $val_id, 'store_id' => getenv('SSLC_STORE_ID'),
    'store_passwd' => getenv('SSLC_STORE_PASSWD'), 'format' => 'json']);
$r = json_decode(file_get_contents($url), true);
// Also compare $r['tran_id'], $r['amount'] and $r['currency_type'] with your order.
if (in_array($r['status'] ?? '', ['VALID', 'VALIDATED'], true)) { /* mark order paid */ }
