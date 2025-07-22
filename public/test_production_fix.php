<?php

require_once '../vendor/autoload.php';

// Load environment variables
if (file_exists('../.env')) {
    $env = parse_ini_file('../.env');
    foreach ($env as $key => $value) {
        $_ENV[$key] = $value;
    }
}

echo "<h1>🚀 Test Production Midtrans Fix</h1>";

$serverKey = $_ENV['MIDTRANS_SERVER_KEY'] ?? null;
$clientKey = $_ENV['MIDTRANS_CLIENT_KEY'] ?? null;
$isProduction = filter_var($_ENV['MIDTRANS_IS_PRODUCTION'] ?? false, FILTER_VALIDATE_BOOLEAN);

echo "<div style='padding: 10px; background-color: #e7f3ff; border: 1px solid #2196F3; margin: 10px 0;'>";
echo "<h3>📋 Configuration Status:</h3>";
echo "<p><strong>Environment:</strong> " . ($isProduction ? "🟢 PRODUCTION" : "🟡 SANDBOX") . "</p>";
echo "<p><strong>Server Key:</strong> " . substr($serverKey, 0, 15) . "...</p>";
echo "<p><strong>Client Key:</strong> " . substr($clientKey, 0, 15) . "...</p>";
echo "</div>";

if ($isProduction && $serverKey && $clientKey) {
    // Set Midtrans Configuration for PRODUCTION
    \Midtrans\Config::$serverKey = $serverKey;
    \Midtrans\Config::$isProduction = true;
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;

    // Test with the same token format as in your error
    $orderId = 'INV202507220416-EE32-' . time() . '-' . substr(md5(uniqid()), 0, 6);

    $params = array(
        'transaction_details' => array(
            'order_id' => $orderId,
            'gross_amount' => 15001,
        ),
        'customer_details' => array(
            'first_name' => 'user',
            'email' => 'user@gmail.com',
            'phone' => '08111222333',
            'billing_address' => array(
                'first_name' => 'user',
                'address' => 'Test Address',
                'city' => 'Jakarta',
                'postal_code' => '12345',
                'country_code' => 'IDN'
            )
        ),
        'item_details' => array(
            array(
                'id' => 1,
                'price' => 1,
                'quantity' => 1,
                'name' => 'Chambre'
            ),
            array(
                'id' => 'shipping',
                'price' => 15000,
                'quantity' => 1,
                'name' => 'Ongkos Kirim'
            )
        )
    );

    echo "<h2>🧪 Production Test:</h2>";
    echo "<p><strong>Order ID:</strong> {$orderId}</p>";
    echo "<p><strong>Amount:</strong> Rp 15,001</p>";

    try {
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        
        echo "<div style='color: green; padding: 15px; border: 1px solid green; background-color: #f0fff0; margin: 10px 0;'>";
        echo "<h3>✅ SUCCESS! Production Token Created</h3>";
        echo "<p><strong>Snap Token:</strong> " . $snapToken . "</p>";
        echo "<p><strong>Environment:</strong> PRODUCTION (Real transactions!)</p>";
        echo "</div>";

        // Create test payment button with PRODUCTION script
        echo "<h2>🎯 Test Production Payment:</h2>";
        echo "<div style='padding: 20px; border: 1px solid #ccc; background-color: #f9f9f9; margin: 10px 0;'>";
        echo "<p><strong>⚠️ WARNING:</strong> This is PRODUCTION mode. Real payment may be charged!</p>";
        echo "<button onclick='pay()' style='padding: 15px 30px; background-color: #ff6b35; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: bold;'>🚀 Test PRODUCTION Payment</button>";
        echo "</div>";

        // Use PRODUCTION Midtrans Script URL
        echo "
        <script src='https://app.midtrans.com/snap/snap.js' data-client-key='{$clientKey}'></script>
        <script type='text/javascript'>
            function pay() {
                console.log('🚀 Starting PRODUCTION payment with token: {$snapToken}');
                snap.pay('{$snapToken}', {
                    onSuccess: function(result) {
                        alert('✅ PRODUCTION Payment SUCCESS!\\nTransaction ID: ' + result.order_id);
                        console.log('✅ Payment success:', result);
                        
                        // Show detailed result
                        document.getElementById('result').innerHTML = 
                            '<div style=\"color: green; padding: 10px; border: 1px solid green; background-color: #f0fff0; margin: 10px 0;\">' +
                            '<h3>✅ Payment Successful!</h3>' +
                            '<p><strong>Transaction ID:</strong> ' + result.order_id + '</p>' +
                            '<p><strong>Status:</strong> ' + result.transaction_status + '</p>' +
                            '<p><strong>Payment Type:</strong> ' + result.payment_type + '</p>' +
                            '</div>';
                    },
                    onPending: function(result) {
                        alert('⏳ Payment PENDING\\nPlease complete your payment');
                        console.log('⏳ Payment pending:', result);
                        
                        document.getElementById('result').innerHTML = 
                            '<div style=\"color: orange; padding: 10px; border: 1px solid orange; background-color: #fff8f0; margin: 10px 0;\">' +
                            '<h3>⏳ Payment Pending</h3>' +
                            '<p>Please complete your payment process</p>' +
                            '</div>';
                    },
                    onError: function(result) {
                        alert('❌ Payment ERROR\\nMessage: ' + (result.status_message || 'Unknown error'));
                        console.log('❌ Payment error:', result);
                        
                        document.getElementById('result').innerHTML = 
                            '<div style=\"color: red; padding: 10px; border: 1px solid red; background-color: #fff0f0; margin: 10px 0;\">' +
                            '<h3>❌ Payment Failed</h3>' +
                            '<p><strong>Error:</strong> ' + (result.status_message || 'Unknown error') + '</p>' +
                            '</div>';
                    },
                    onClose: function() {
                        console.log('💭 Payment popup closed');
                        alert('💭 Payment popup was closed');
                    }
                });
            }
        </script>";
        
        echo "<div id='result' style='margin-top: 20px;'></div>";

    } catch (Exception $e) {
        echo "<div style='color: red; padding: 15px; border: 1px solid red; background-color: #fff0f0; margin: 10px 0;'>";
        echo "<h3>❌ ERROR! Production Test Failed</h3>";
        echo "<p><strong>Message:</strong> " . $e->getMessage() . "</p>";
        echo "<p><strong>Code:</strong> " . $e->getCode() . "</p>";
        echo "</div>";

        echo "<h3>🔧 Troubleshooting Steps:</h3>";
        echo "<ol>";
        echo "<li><strong>Verify Server Key:</strong> Log in to <a href='https://dashboard.midtrans.com' target='_blank'>Midtrans Production Dashboard</a></li>";
        echo "<li><strong>Check Account Status:</strong> Ensure your Midtrans account is approved for production</li>";
        echo "<li><strong>API Access:</strong> Verify that API access is enabled for production</li>";
        echo "<li><strong>Network:</strong> Ensure your server can reach production Midtrans APIs</li>";
        echo "</ol>";
    }

    // Show comparison
    echo "<h2>📊 Environment Comparison:</h2>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
    echo "<tr style='background-color: #f2f2f2;'><th>Component</th><th>Sandbox</th><th>Production</th><th>Your Setting</th></tr>";
    echo "<tr><td>Script URL</td><td>app.sandbox.midtrans.com</td><td>app.midtrans.com</td><td>" . ($isProduction ? "✅ Production" : "❌ Sandbox") . "</td></tr>";
    echo "<tr><td>Server Key</td><td>SB-Mid-server-xxx</td><td>Mid-server-xxx</td><td>" . (strpos($serverKey, 'Mid-server-') === 0 ? "✅ Production" : "❌ Invalid") . "</td></tr>";
    echo "<tr><td>Client Key</td><td>SB-Mid-client-xxx</td><td>Mid-client-xxx</td><td>" . (strpos($clientKey, 'Mid-client-') === 0 ? "✅ Production" : "❌ Invalid") . "</td></tr>";
    echo "</table>";

} else {
    echo "<div style='color: red; padding: 15px; border: 1px solid red; background-color: #fff0f0; margin: 10px 0;'>";
    echo "<h3>❌ Configuration Error!</h3>";
    if (!$isProduction) {
        echo "<p>❌ Production mode is not enabled. Set MIDTRANS_IS_PRODUCTION=true in .env</p>";
    }
    if (!$serverKey) {
        echo "<p>❌ Server key is missing. Check MIDTRANS_SERVER_KEY in .env</p>";
    }
    if (!$clientKey) {
        echo "<p>❌ Client key is missing. Check MIDTRANS_CLIENT_KEY in .env</p>";
    }
    echo "</div>";
}

echo "<h2>🔧 Quick Fix Checklist:</h2>";
echo "<div style='background-color: #f9f9f9; padding: 15px; border: 1px solid #ddd; margin: 10px 0;'>";
echo "<ol>";
echo "<li>✅ Script URL sudah diperbaiki untuk production di process.blade.php</li>";
echo "<li>🔍 Verifikasi server key production di Midtrans Dashboard</li>";
echo "<li>🔍 Pastikan akun Midtrans sudah diapprove untuk production</li>";
echo "<li>🔍 Test ulang checkout di aplikasi Laravel Anda</li>";
echo "</ol>";
echo "</div>";

?>
