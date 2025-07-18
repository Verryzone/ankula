<?php

require_once '../vendor/autoload.php';

// Load environment variables
if (file_exists('../.env')) {
    $env = parse_ini_file('../.env');
    foreach ($env as $key => $value) {
        $_ENV[$key] = $value;
    }
}

echo "<h1>Midtrans Debug Tool</h1>";

// Get configuration from .env
$serverKey = $_ENV['MIDTRANS_SERVER_KEY'] ?? 'SB-Mid-server-kRdJDfABHpDhj7kAC_W32OPx';
$isProduction = filter_var($_ENV['MIDTRANS_IS_PRODUCTION'] ?? false, FILTER_VALIDATE_BOOLEAN);

echo "<h2>Configuration:</h2>";
echo "Server Key: " . substr($serverKey, 0, 10) . "..." . "<br>";
echo "Is Production: " . ($isProduction ? 'Yes' : 'No') . "<br>";
echo "PHP Version: " . phpversion() . "<br>";

// Set Midtrans Configuration
\Midtrans\Config::$serverKey = $serverKey;
\Midtrans\Config::$isProduction = $isProduction;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

echo "<h2>Test Transaction:</h2>";

// Test transaction with unique order ID
$orderId = 'DEBUG-' . date('YmdHis') . '-' . substr(md5(uniqid()), 0, 6);

$params = array(
    'transaction_details' => array(
        'order_id' => $orderId,
        'gross_amount' => 10000,
    ),
    'customer_details' => array(
        'first_name' => 'Debug',
        'last_name' => 'Test',
        'email' => 'debug@test.com',
        'phone' => '08111222333',
    ),
    'item_details' => array(
        array(
            'id' => 'item1',
            'price' => 10000,
            'quantity' => 1,
            'name' => 'Debug Test Item'
        )
    )
);

echo "Order ID: " . $orderId . "<br>";
echo "Gross Amount: Rp 10,000<br>";

try {
    $snapToken = \Midtrans\Snap::getSnapToken($params);
    echo "<div style='color: green;'>";
    echo "<h3>✅ Success!</h3>";
    echo "Snap Token: " . substr($snapToken, 0, 20) . "...<br>";
    echo "</div>";
    
    // Test payment page
    echo "<h2>Test Payment:</h2>";
    echo "<button onclick='pay()'>Pay Now</button>";
    echo "
    <script src='https://app.sandbox.midtrans.com/snap/snap.js' data-client-key='" . ($_ENV['MIDTRANS_CLIENT_KEY'] ?? 'SB-Mid-client-AUrh7bQ7GWj7hgwJ') . "'></script>
    <script type='text/javascript'>
        function pay() {
            snap.pay('{$snapToken}', {
                onSuccess: function(result) {
                    alert('Payment success!');
                    console.log(result);
                },
                onPending: function(result) {
                    alert('Waiting for payment');
                    console.log(result);
                },
                onError: function(result) {
                    alert('Payment failed');
                    console.log(result);
                }
            });
        }
    </script>";
    
} catch (Exception $e) {
    echo "<div style='color: red;'>";
    echo "<h3>❌ Error!</h3>";
    echo "Message: " . $e->getMessage() . "<br>";
    echo "Code: " . $e->getCode() . "<br>";
    echo "</div>";
    
    echo "<h3>Common Solutions:</h3>";
    echo "<ul>";
    echo "<li>Check if server key is correct in .env file</li>";
    echo "<li>Ensure internet connection is stable</li>";
    echo "<li>Verify Midtrans account is active</li>";
    echo "<li>Check if order_id is unique</li>";
    echo "</ul>";
}

echo "<h2>Database Check:</h2>";

// Check database connectivity
try {
    $pdo = new PDO(
        "mysql:host=" . ($_ENV['DB_HOST'] ?? 'localhost') . ";dbname=" . ($_ENV['DB_DATABASE'] ?? 'test'),
        $_ENV['DB_USERNAME'] ?? 'root',
        $_ENV['DB_PASSWORD'] ?? ''
    );
    
    echo "✅ Database connection: OK<br>";
    
    // Check payments table
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM payments WHERE status = 'pending'");
    $pendingCount = $stmt->fetch()['count'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM payments WHERE snap_token_expires_at < NOW() AND status = 'pending'");
    $expiredCount = $stmt->fetch()['count'];
    
    echo "Pending payments: " . $pendingCount . "<br>";
    echo "Expired pending payments: " . $expiredCount . "<br>";
    
    if ($expiredCount > 0) {
        echo "<button onclick='cleanupExpired()'>Cleanup Expired Payments</button><br>";
        echo "<script>
            function cleanupExpired() {
                if (confirm('Clean up {$expiredCount} expired payments?')) {
                    fetch('cleanup_expired.php', {method: 'POST'})
                        .then(response => response.text())
                        .then(data => {
                            alert(data);
                            location.reload();
                        });
                }
            }
        </script>";
    }
    
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "<br>";
}

?>
