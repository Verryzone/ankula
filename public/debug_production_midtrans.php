<?php

require_once '../vendor/autoload.php';

// Load environment variables
if (file_exists('../.env')) {
    $env = parse_ini_file('../.env');
    foreach ($env as $key => $value) {
        $_ENV[$key] = $value;
    }
}

echo "<h1>🔍 Midtrans Production Debug Tool</h1>";

// Get configuration from .env
$serverKey = $_ENV['MIDTRANS_SERVER_KEY'] ?? null;
$clientKey = $_ENV['MIDTRANS_CLIENT_KEY'] ?? null;
$isProduction = filter_var($_ENV['MIDTRANS_IS_PRODUCTION'] ?? false, FILTER_VALIDATE_BOOLEAN);

echo "<h2>📋 Configuration Check:</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><td><strong>Server Key</strong></td><td>" . ($serverKey ? substr($serverKey, 0, 15) . "..." : "❌ NOT SET") . "</td></tr>";
echo "<tr><td><strong>Client Key</strong></td><td>" . ($clientKey ? substr($clientKey, 0, 15) . "..." : "❌ NOT SET") . "</td></tr>";
echo "<tr><td><strong>Is Production</strong></td><td>" . ($isProduction ? "✅ Yes (Production)" : "❌ No (Sandbox)") . "</td></tr>";
echo "<tr><td><strong>PHP Version</strong></td><td>" . phpversion() . "</td></tr>";
echo "</table><br>";

// Validate keys format
if ($serverKey) {
    if ($isProduction) {
        if (strpos($serverKey, 'Mid-server-') === 0) {
            echo "✅ <strong>Server Key Format:</strong> Correct for Production<br>";
        } else {
            echo "❌ <strong>Server Key Format:</strong> Invalid for Production (should start with 'Mid-server-')<br>";
        }
    } else {
        if (strpos($serverKey, 'SB-Mid-server-') === 0) {
            echo "✅ <strong>Server Key Format:</strong> Correct for Sandbox<br>";
        } else {
            echo "❌ <strong>Server Key Format:</strong> Invalid for Sandbox (should start with 'SB-Mid-server-')<br>";
        }
    }
}

if ($clientKey) {
    if ($isProduction) {
        if (strpos($clientKey, 'Mid-client-') === 0) {
            echo "✅ <strong>Client Key Format:</strong> Correct for Production<br>";
        } else {
            echo "❌ <strong>Client Key Format:</strong> Invalid for Production (should start with 'Mid-client-')<br>";
        }
    } else {
        if (strpos($clientKey, 'SB-Mid-client-') === 0) {
            echo "✅ <strong>Client Key Format:</strong> Correct for Sandbox<br>";
        } else {
            echo "❌ <strong>Client Key Format:</strong> Invalid for Sandbox (should start with 'SB-Mid-client-')<br>";
        }
    }
}

echo "<br>";

// Test Midtrans Configuration
if ($serverKey && $clientKey) {
    echo "<h2>🧪 Midtrans Connection Test:</h2>";

    // Set Midtrans Configuration
    \Midtrans\Config::$serverKey = $serverKey;
    \Midtrans\Config::$isProduction = $isProduction;
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;

    // Test transaction with unique order ID
    $orderId = 'TEST-PROD-' . date('YmdHis') . '-' . substr(md5(uniqid()), 0, 6);

    $params = array(
        'transaction_details' => array(
            'order_id' => $orderId,
            'gross_amount' => 10000,
        ),
        'customer_details' => array(
            'first_name' => 'Production',
            'last_name' => 'Test',
            'email' => 'test@production.com',
            'phone' => '08111222333',
        ),
        'item_details' => array(
            array(
                'id' => 'item1',
                'price' => 10000,
                'quantity' => 1,
                'name' => 'Production Test Item'
            )
        )
    );

    echo "Order ID: <strong>" . $orderId . "</strong><br>";
    echo "Environment: <strong>" . ($isProduction ? "PRODUCTION" : "SANDBOX") . "</strong><br>";
    echo "Amount: <strong>Rp 10,000</strong><br><br>";

    try {
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        echo "<div style='color: green; padding: 10px; border: 1px solid green; background-color: #f0fff0;'>";
        echo "<h3>✅ SUCCESS! Midtrans Connection Working</h3>";
        echo "Snap Token: " . substr($snapToken, 0, 20) . "...<br>";
        echo "Environment: " . ($isProduction ? "PRODUCTION" : "SANDBOX") . "<br>";
        echo "</div>";

        // Test payment page
        echo "<h2>🎯 Test Payment Page:</h2>";
        echo "<div style='padding: 20px; border: 1px solid #ccc; background-color: #f9f9f9;'>";
        echo "<p>Click the button below to test the payment process:</p>";
        echo "<button onclick='pay()' style='padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;'>🚀 Test Payment</button>";
        echo "</div>";

        $scriptUrl = $isProduction ? "https://app.midtrans.com/snap/snap.js" : "https://app.sandbox.midtrans.com/snap/snap.js";
        
        echo "
        <script src='{$scriptUrl}' data-client-key='{$clientKey}'></script>
        <script type='text/javascript'>
            function pay() {
                snap.pay('{$snapToken}', {
                    onSuccess: function(result) {
                        alert('✅ Payment success! Environment: " . ($isProduction ? "PRODUCTION" : "SANDBOX") . "');
                        console.log('Payment success:', result);
                    },
                    onPending: function(result) {
                        alert('⏳ Payment pending');
                        console.log('Payment pending:', result);
                    },
                    onError: function(result) {
                        alert('❌ Payment failed');
                        console.log('Payment error:', result);
                    },
                    onClose: function() {
                        alert('💭 Payment popup closed');
                    }
                });
            }
        </script>";

    } catch (Exception $e) {
        echo "<div style='color: red; padding: 10px; border: 1px solid red; background-color: #fff0f0;'>";
        echo "<h3>❌ ERROR! Midtrans Connection Failed</h3>";
        echo "<strong>Message:</strong> " . $e->getMessage() . "<br>";
        echo "<strong>Code:</strong> " . $e->getCode() . "<br>";
        echo "</div>";

        echo "<h3>🔧 Common Solutions:</h3>";
        echo "<ul>";
        echo "<li>✅ Verify server key is correct in Midtrans Dashboard</li>";
        echo "<li>✅ Check if Midtrans account is active and verified</li>";
        echo "<li>✅ Ensure production environment is enabled in Midtrans Dashboard</li>";
        echo "<li>✅ Verify internet connection is stable</li>";
        echo "<li>✅ Check if order_id format is correct and unique</li>";
        echo "<li>✅ Make sure server key matches production/sandbox environment</li>";
        echo "</ul>";
    }
} else {
    echo "<div style='color: red; padding: 10px; border: 1px solid red; background-color: #fff0f0;'>";
    echo "<h3>❌ Configuration Error!</h3>";
    echo "Server Key or Client Key is missing. Please check your .env file.";
    echo "</div>";
}

// Environment recommendation
echo "<h2>🎯 Environment Recommendations:</h2>";
if ($isProduction) {
    echo "<div style='color: green; padding: 10px; border: 1px solid green; background-color: #f0fff0;'>";
    echo "<h3>🟢 PRODUCTION MODE ACTIVE</h3>";
    echo "<p><strong>⚠️ WARNING:</strong> You are in production mode. Real transactions will be processed!</p>";
    echo "<ul>";
    echo "<li>✅ Make sure your server keys are from production dashboard</li>";
    echo "<li>✅ Test with real payment methods</li>";
    echo "<li>✅ Monitor transactions in Midtrans production dashboard</li>";
    echo "</ul>";
    echo "</div>";
} else {
    echo "<div style='color: orange; padding: 10px; border: 1px solid orange; background-color: #fff8f0;'>";
    echo "<h3>🟡 SANDBOX MODE ACTIVE</h3>";
    echo "<p>You are in sandbox/testing mode. No real money will be charged.</p>";
    echo "<ul>";
    echo "<li>✅ Use test credit card numbers for testing</li>";
    echo "<li>✅ Check transactions in Midtrans sandbox dashboard</li>";
    echo "<li>✅ Switch to production when ready to go live</li>";
    echo "</ul>";
    echo "</div>";
}

echo "<br><h2>📝 Next Steps:</h2>";
echo "<ol>";
echo "<li><strong>Fix Configuration:</strong> Make sure server/client keys match the environment</li>";
echo "<li><strong>Test Payment:</strong> Use the test button above to verify connection</li>";
echo "<li><strong>Check Laravel App:</strong> Ensure your app uses the correct script URL</li>";
echo "<li><strong>Monitor Logs:</strong> Check storage/logs/laravel.log for any errors</li>";
echo "</ol>";

?>
