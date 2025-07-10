<?php

require_once '../vendor/autoload.php';

// Set your Merchant Server Key
\Midtrans\Config::$serverKey = 'SB-Mid-server-kRdJDfABHpDhj7kAC_W32OPx';
// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
\Midtrans\Config::$isProduction = false;
// Set sanitization on (default)
\Midtrans\Config::$isSanitized = true;
// Set 3DS transaction for credit card to true
\Midtrans\Config::$is3ds = true;

$params = array(
    'transaction_details' => array(
        'order_id' => 'test-order-' . time(),
        'gross_amount' => 10000,
    ),
    'customer_details' => array(
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@example.com',
        'phone' => '08111222333',
    ),
);

try {
    $snapToken = \Midtrans\Snap::getSnapToken($params);
    echo "Success! Snap Token: " . $snapToken;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
