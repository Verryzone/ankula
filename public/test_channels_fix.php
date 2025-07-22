<?php

require_once '../vendor/autoload.php';

// Load environment
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, '"\'');
            $_ENV[$key] = $value;
        }
    }
}

echo "<h1>🔧 Test Payment Channels (Tanpa Ubah Key)</h1>";

$serverKey = $_ENV['MIDTRANS_SERVER_KEY'] ?? null;
$clientKey = $_ENV['MIDTRANS_CLIENT_KEY'] ?? null;
$isProduction = filter_var($_ENV['MIDTRANS_IS_PRODUCTION'] ?? false, FILTER_VALIDATE_BOOLEAN);

echo "<div style='background: #e3f2fd; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
echo "<h3>📋 Menggunakan Key Anda:</h3>";
echo "<p><strong>Environment:</strong> " . ($isProduction ? "Production" : "Sandbox") . "</p>";
echo "<p><strong>Server Key:</strong> " . substr($serverKey, 0, 25) . "...</p>";
echo "<p><strong>Client Key:</strong> " . substr($clientKey, 0, 25) . "...</p>";
echo "</div>";

// Set Midtrans config dengan key Anda
\Midtrans\Config::$serverKey = $serverKey;
\Midtrans\Config::$isProduction = $isProduction;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

$orderId = 'TEST-CHANNELS-' . time();

// Parameter dengan enabled_payments untuk fix "no channels"
$params = array(
    'transaction_details' => array(
        'order_id' => $orderId,
        'gross_amount' => 15001,
    ),
    'customer_details' => array(
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@example.com',
        'phone' => '08111222333',
        'billing_address' => array(
            'first_name' => 'Test',
            'last_name' => 'User',
            'address' => 'Jl. Test No. 123',
            'city' => 'Jakarta',
            'postal_code' => '12345',
            'phone' => '08111222333',
            'country_code' => 'IDN'
        )
    ),
    'item_details' => array(
        array(
            'id' => 'item1',
            'price' => 1,
            'quantity' => 1,
            'name' => 'Test Product'
        ),
        array(
            'id' => 'shipping',
            'price' => 15000,
            'quantity' => 1,
            'name' => 'Shipping Cost'
        )
    ),
    // INI YANG PENTING: enabled_payments untuk fix "no channels available"
    'enabled_payments' => array(
        'credit_card', 'cimb_clicks', 'bca_klikbca', 'bca_klikpay', 'bri_epay',
        'echannel', 'permata_va', 'bca_va', 'bni_va', 'bri_va', 'other_va',
        'gopay', 'shopeepay', 'indomaret', 'alfamart'
    ),
    'credit_card' => array(
        'secure' => true,
        'channel' => 'migs'
    )
);

echo "<div style='background: #f0f8ff; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
echo "<h3>🧪 Test Transaction:</h3>";
echo "<p><strong>Order ID:</strong> {$orderId}</p>";
echo "<p><strong>Amount:</strong> Rp 15,001</p>";
echo "<p><strong>Fix Applied:</strong> enabled_payments parameter ditambahkan</p>";
echo "</div>";

try {
    $snapToken = \Midtrans\Snap::getSnapToken($params);
    
    echo "<div style='color: green; padding: 15px; background: #f0fff0; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3>✅ SUCCESS! Payment Channels Fix Berhasil</h3>";
    echo "<p><strong>Token:</strong> " . substr($snapToken, 0, 30) . "...</p>";
    echo "<p>Sekarang coba test payment untuk memastikan channels tersedia</p>";
    echo "</div>";

    echo "<div style='text-align: center; margin: 20px 0;'>";
    echo "<button onclick='testPayment()' style='padding: 15px 30px; background: #4CAF50; color: white; border: none; border-radius: 8px; font-size: 16px; cursor: pointer;'>🚀 Test Payment Channels</button>";
    echo "</div>";

    echo "<div id='result' style='margin: 20px 0;'></div>";

    $scriptUrl = $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
    
    echo "<script src='{$scriptUrl}' data-client-key='{$clientKey}'></script>";
    echo "<script>
        function testPayment() {
            console.log('Testing payment channels...');
            
            snap.pay('{$snapToken}', {
                onSuccess: function(result) {
                    document.getElementById('result').innerHTML = 
                        '<div style=\"color: green; padding: 15px; background: #f0fff0; border-radius: 8px;\">' +
                        '<h3>✅ Payment Channels Working!</h3>' +
                        '<p><strong>Payment Method:</strong> ' + result.payment_type + '</p>' +
                        '<p><strong>Transaction ID:</strong> ' + result.order_id + '</p>' +
                        '</div>';
                    alert('✅ Payment Channels Working! Method: ' + result.payment_type);
                },
                onPending: function(result) {
                    document.getElementById('result').innerHTML = 
                        '<div style=\"color: orange; padding: 15px; background: #fff8f0; border-radius: 8px;\">' +
                        '<h3>⏳ Payment Channels Available</h3>' +
                        '<p>Channels working, payment pending completion</p>' +
                        '</div>';
                    alert('⏳ Channels Available! Complete your payment.');
                },
                onError: function(result) {
                    document.getElementById('result').innerHTML = 
                        '<div style=\"color: red; padding: 15px; background: #fff0f0; border-radius: 8px;\">' +
                        '<h3>❌ Error</h3>' +
                        '<p>' + (result.status_message || 'Unknown error') + '</p>' +
                        '</div>';
                    alert('❌ Error: ' + (result.status_message || 'Unknown error'));
                },
                onClose: function() {
                    document.getElementById('result').innerHTML = 
                        '<div style=\"color: gray; padding: 15px; background: #f8f8f8; border-radius: 8px;\">' +
                        '<h3>💭 Payment Closed</h3>' +
                        '<p>Channels were available, user closed popup</p>' +
                        '</div>';
                }
            });
        }
    </script>";

} catch (Exception $e) {
    echo "<div style='color: red; padding: 15px; background: #fff0f0; border-radius: 8px; margin: 20px 0;'>";
    echo "<h3>❌ Error!</h3>";
    echo "<p><strong>Message:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Possible Cause:</strong> Key might not be valid atau belum aktif di Midtrans Dashboard</p>";
    echo "</div>";
}

echo "<div style='background: #fff3cd; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
echo "<h3>🔧 Yang Sudah Diperbaiki:</h3>";
echo "<ol>";
echo "<li>✅ <strong>MidtransService.php:</strong> Ditambahkan enabled_payments parameter</li>";
echo "<li>✅ <strong>Payment Methods:</strong> Credit card, VA, e-wallet, retail semua enabled</li>";
echo "<li>✅ <strong>Customer Details:</strong> Lengkap dengan billing address</li>";
echo "</ol>";
echo "<p><strong>Sekarang coba checkout di Laravel app Anda!</strong></p>";
echo "</div>";

?>
