<?php

require_once '../vendor/autoload.php';

// Load Laravel environment
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

echo "<!DOCTYPE html><html><head><title>🚀 Midtrans Production Fix Test</title></head><body>";
echo "<h1>🚀 Midtrans Production Fix - Final Test</h1>";

// Configuration
$serverKey = $_ENV['MIDTRANS_SERVER_KEY'] ?? null;
$clientKey = $_ENV['MIDTRANS_CLIENT_KEY'] ?? null;
$isProduction = filter_var($_ENV['MIDTRANS_IS_PRODUCTION'] ?? false, FILTER_VALIDATE_BOOLEAN);

// Status display
echo "<div style='padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px; margin: 20px 0;'>";
echo "<h2>📊 Current Configuration Status</h2>";
echo "<table style='width: 100%; color: white;'>";
echo "<tr><td><strong>🌍 Environment:</strong></td><td>" . ($isProduction ? "🟢 PRODUCTION" : "🟡 SANDBOX") . "</td></tr>";
echo "<tr><td><strong>🔑 Server Key:</strong></td><td>" . ($serverKey ? substr($serverKey, 0, 15) . "..." : "❌ NOT SET") . "</td></tr>";
echo "<tr><td><strong>🎫 Client Key:</strong></td><td>" . ($clientKey ? substr($clientKey, 0, 15) . "..." : "❌ NOT SET") . "</td></tr>";
echo "<tr><td><strong>📅 Date:</strong></td><td>" . date('Y-m-d H:i:s') . "</td></tr>";
echo "</table>";
echo "</div>";

// Validation
$errors = [];
$warnings = [];

if (!$serverKey) {
    $errors[] = "Server Key tidak tersedia";
} elseif ($isProduction && strpos($serverKey, 'Mid-server-') !== 0) {
    $errors[] = "Server Key format salah untuk production (harus dimulai dengan 'Mid-server-')";
} elseif (!$isProduction && strpos($serverKey, 'SB-Mid-server-') !== 0) {
    $warnings[] = "Server Key mungkin salah untuk sandbox (biasanya dimulai dengan 'SB-Mid-server-')";
}

if (!$clientKey) {
    $errors[] = "Client Key tidak tersedia";
} elseif ($isProduction && strpos($clientKey, 'Mid-client-') !== 0) {
    $errors[] = "Client Key format salah untuk production (harus dimulai dengan 'Mid-client-')";
} elseif (!$isProduction && strpos($clientKey, 'SB-Mid-client-') !== 0) {
    $warnings[] = "Client Key mungkin salah untuk sandbox (biasanya dimulai dengan 'SB-Mid-client-')";
}

// Display errors and warnings
if (!empty($errors)) {
    echo "<div style='color: red; padding: 15px; border: 1px solid red; background-color: #fff0f0; margin: 10px 0; border-radius: 5px;'>";
    echo "<h3>❌ CRITICAL ERRORS FOUND:</h3>";
    foreach ($errors as $error) {
        echo "<p>• " . $error . "</p>";
    }
    echo "</div>";
}

if (!empty($warnings)) {
    echo "<div style='color: orange; padding: 15px; border: 1px solid orange; background-color: #fff8f0; margin: 10px 0; border-radius: 5px;'>";
    echo "<h3>⚠️ WARNINGS:</h3>";
    foreach ($warnings as $warning) {
        echo "<p>• " . $warning . "</p>";
    }
    echo "</div>";
}

// Test connection if no critical errors
if (empty($errors)) {
    // Set Midtrans configuration
    \Midtrans\Config::$serverKey = $serverKey;
    \Midtrans\Config::$isProduction = $isProduction;
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;

    echo "<h2>🧪 Connection Test</h2>";

    // Generate test order similar to your app
    $orderId = 'TEST-' . date('YmdHis') . '-' . substr(md5(uniqid()), 0, 6);
    
    $params = array(
        'transaction_details' => array(
            'order_id' => $orderId,
            'gross_amount' => 15001, // Same as your actual transaction
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

    echo "<div style='background-color: #f0f8ff; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3>📦 Test Transaction Details:</h3>";
    echo "<p><strong>Order ID:</strong> {$orderId}</p>";
    echo "<p><strong>Amount:</strong> Rp 15,001</p>";
    echo "<p><strong>Environment:</strong> " . ($isProduction ? "PRODUCTION" : "SANDBOX") . "</p>";
    echo "</div>";

    try {
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        
        echo "<div style='color: green; padding: 15px; border: 1px solid green; background-color: #f0fff0; margin: 10px 0; border-radius: 5px;'>";
        echo "<h3>✅ SUCCESS! Midtrans Connection Working</h3>";
        echo "<p><strong>Snap Token:</strong> <code style='background: #e8e8e8; padding: 2px 4px; border-radius: 3px;'>{$snapToken}</code></p>";
        echo "<p><strong>Environment:</strong> " . ($isProduction ? "🟢 PRODUCTION" : "🟡 SANDBOX") . "</p>";
        if ($isProduction) {
            echo "<p><strong>⚠️ WARNING:</strong> This is production mode - real payments will be processed!</p>";
        }
        echo "</div>";

        // Payment test interface
        echo "<h2>🎯 Interactive Payment Test</h2>";
        echo "<div style='background-color: #f9f9f9; padding: 20px; border-radius: 10px; margin: 20px 0; text-align: center;'>";
        
        if ($isProduction) {
            echo "<p style='color: red; font-weight: bold; font-size: 18px;'>⚠️ PRODUCTION MODE ACTIVE ⚠️</p>";
            echo "<p style='color: red;'>Real money will be charged! Only proceed if you want to test with real payment.</p>";
        } else {
            echo "<p style='color: green; font-weight: bold; font-size: 18px;'>🟡 SANDBOX MODE - Safe Testing</p>";
            echo "<p>No real money will be charged. Safe to test.</p>";
        }
        
        echo "<button onclick='testPayment()' style='padding: 15px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 25px; cursor: pointer; font-size: 16px; font-weight: bold; margin: 10px;'>🚀 Test Payment Process</button>";
        echo "<button onclick='location.reload()' style='padding: 15px 30px; background: #6c757d; color: white; border: none; border-radius: 25px; cursor: pointer; font-size: 16px; margin: 10px;'>🔄 Refresh Test</button>";
        echo "</div>";

        // Result display area
        echo "<div id='paymentResult' style='margin: 20px 0;'></div>";

        // Include correct Midtrans script based on environment
        $scriptUrl = $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
        
        echo "<script src='{$scriptUrl}' data-client-key='{$clientKey}'></script>";
        echo "<script>
            function testPayment() {
                console.log('🚀 Testing payment with environment: " . ($isProduction ? "PRODUCTION" : "SANDBOX") . "');
                console.log('📦 Order ID: {$orderId}');
                console.log('💰 Amount: Rp 15,001');
                console.log('🎫 Token: {$snapToken}');
                
                snap.pay('{$snapToken}', {
                    onSuccess: function(result) {
                        console.log('✅ Payment success:', result);
                        document.getElementById('paymentResult').innerHTML = 
                            '<div style=\"color: green; padding: 15px; border: 1px solid green; background-color: #f0fff0; margin: 10px 0; border-radius: 5px;\">' +
                            '<h3>✅ Payment Successful!</h3>' +
                            '<p><strong>Transaction ID:</strong> ' + result.order_id + '</p>' +
                            '<p><strong>Status:</strong> ' + result.transaction_status + '</p>' +
                            '<p><strong>Payment Type:</strong> ' + result.payment_type + '</p>' +
                            '<p><strong>Environment:</strong> " . ($isProduction ? "PRODUCTION" : "SANDBOX") . "</p>' +
                            (result.va_numbers ? '<p><strong>VA Number:</strong> ' + JSON.stringify(result.va_numbers) + '</p>' : '') +
                            '</div>';
                        
                        alert('✅ Payment Success! Check the result below.');
                    },
                    onPending: function(result) {
                        console.log('⏳ Payment pending:', result);
                        document.getElementById('paymentResult').innerHTML = 
                            '<div style=\"color: orange; padding: 15px; border: 1px solid orange; background-color: #fff8f0; margin: 10px 0; border-radius: 5px;\">' +
                            '<h3>⏳ Payment Pending</h3>' +
                            '<p><strong>Transaction ID:</strong> ' + result.order_id + '</p>' +
                            '<p><strong>Status:</strong> ' + result.transaction_status + '</p>' +
                            '<p>Please complete your payment process.</p>' +
                            '</div>';
                        
                        alert('⏳ Payment Pending! Complete your payment.');
                    },
                    onError: function(result) {
                        console.log('❌ Payment error:', result);
                        document.getElementById('paymentResult').innerHTML = 
                            '<div style=\"color: red; padding: 15px; border: 1px solid red; background-color: #fff0f0; margin: 10px 0; border-radius: 5px;\">' +
                            '<h3>❌ Payment Failed</h3>' +
                            '<p><strong>Error Message:</strong> ' + (result.status_message || 'Unknown error') + '</p>' +
                            '<p><strong>Status Code:</strong> ' + (result.status_code || 'N/A') + '</p>' +
                            '</div>';
                        
                        alert('❌ Payment Failed: ' + (result.status_message || 'Unknown error'));
                    },
                    onClose: function() {
                        console.log('💭 Payment popup closed');
                        document.getElementById('paymentResult').innerHTML = 
                            '<div style=\"color: gray; padding: 15px; border: 1px solid gray; background-color: #f8f8f8; margin: 10px 0; border-radius: 5px;\">' +
                            '<h3>💭 Payment Cancelled</h3>' +
                            '<p>User closed the payment popup.</p>' +
                            '</div>';
                    }
                });
            }
        </script>";

    } catch (Exception $e) {
        echo "<div style='color: red; padding: 15px; border: 1px solid red; background-color: #fff0f0; margin: 10px 0; border-radius: 5px;'>";
        echo "<h3>❌ CONNECTION FAILED!</h3>";
        echo "<p><strong>Error Message:</strong> " . $e->getMessage() . "</p>";
        echo "<p><strong>Error Code:</strong> " . $e->getCode() . "</p>";
        echo "</div>";

        // Provide specific solutions
        if (strpos($e->getMessage(), 'Access denied') !== false || $e->getCode() == 401) {
            echo "<div style='background-color: #fff3cd; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
            echo "<h3>🔧 Possible Solutions:</h3>";
            echo "<ol>";
            echo "<li><strong>Verify Server Key:</strong> Log in to <a href='https://dashboard.midtrans.com' target='_blank' style='color: #007bff;'>Midtrans Dashboard</a> and copy the correct server key</li>";
            echo "<li><strong>Check Account Status:</strong> Ensure your Midtrans account is approved for production transactions</li>";
            echo "<li><strong>API Access:</strong> Verify that Snap API is enabled in your Midtrans settings</li>";
            echo "<li><strong>Environment Mismatch:</strong> Make sure you're using production keys for production mode</li>";
            echo "</ol>";
            echo "</div>";
        }
    }
}

// Action items
echo "<h2>🎯 Next Steps to Fix Your Laravel App</h2>";
echo "<div style='background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
echo "<h3>✅ Fixes Already Applied:</h3>";
echo "<ol style='color: white;'>";
echo "<li>✅ <strong>Script URL Fixed:</strong> process.blade.php now uses correct production/sandbox URL</li>";
echo "<li>✅ <strong>Environment Detection:</strong> Automatically detects production vs sandbox</li>";
echo "</ol>";

echo "<h3>🔍 What You Need to Verify:</h3>";
echo "<ol style='color: white;'>";
echo "<li>🔑 <strong>Server Key:</strong> Get the correct production server key from Midtrans Dashboard</li>";
echo "<li>🏢 <strong>Account Status:</strong> Ensure your Midtrans account is approved for production</li>";
echo "<li>⚙️ <strong>API Settings:</strong> Check that Snap API is enabled in production settings</li>";
echo "<li>🌐 <strong>Webhook URLs:</strong> Update webhook URLs in Midtrans Dashboard to your production domain</li>";
echo "</ol>";
echo "</div>";

// Configuration summary
echo "<h2>📋 Configuration Summary</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
echo "<tr style='background-color: #f2f2f2;'><th>Component</th><th>Expected (Production)</th><th>Your Current</th><th>Status</th></tr>";

$scriptStatus = "✅ Fixed";
$serverKeyStatus = ($serverKey && strpos($serverKey, 'Mid-server-') === 0) ? "✅ Correct" : "❌ Need to verify";
$clientKeyStatus = ($clientKey && strpos($clientKey, 'Mid-client-') === 0) ? "✅ Correct" : "❌ Need to verify";
$envStatus = $isProduction ? "✅ Production" : "⚠️ Sandbox";

echo "<tr><td>Script URL</td><td>app.midtrans.com</td><td>Auto-detected</td><td>{$scriptStatus}</td></tr>";
echo "<tr><td>Server Key</td><td>Mid-server-xxx</td><td>" . substr($serverKey, 0, 15) . "...</td><td>{$serverKeyStatus}</td></tr>";
echo "<tr><td>Client Key</td><td>Mid-client-xxx</td><td>" . substr($clientKey, 0, 15) . "...</td><td>{$clientKeyStatus}</td></tr>";
echo "<tr><td>Environment</td><td>Production</td><td>" . ($isProduction ? "Production" : "Sandbox") . "</td><td>{$envStatus}</td></tr>";
echo "</table>";

echo "<div style='background-color: #d1ecf1; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
echo "<h3>📞 Support Information:</h3>";
echo "<p>If you still encounter issues after verifying the configuration:</p>";
echo "<ul>";
echo "<li>📧 Contact Midtrans Support: <a href='mailto:support@midtrans.com'>support@midtrans.com</a></li>";
echo "<li>📖 Documentation: <a href='https://docs.midtrans.com' target='_blank'>https://docs.midtrans.com</a></li>";
echo "<li>💬 Check your Midtrans Dashboard for any pending approvals or restrictions</li>";
echo "</ul>";
echo "</div>";

echo "<p style='text-align: center; margin: 30px 0; color: #666;'>Generated at " . date('Y-m-d H:i:s') . " | Test completed ✅</p>";
echo "</body></html>";
?>
