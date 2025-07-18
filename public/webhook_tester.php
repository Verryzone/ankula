<?php

require_once '../vendor/autoload.php';

// Load environment variables
if (file_exists('../.env')) {
    $env = parse_ini_file('../.env');
    foreach ($env as $key => $value) {
        $_ENV[$key] = $value;
    }
}

echo "<h1>Midtrans Webhook Tester</h1>";

// Test webhook endpoint
$webhookUrl = 'http://localhost:8000/payment/notification';

echo "<h2>Configuration:</h2>";
echo "Webhook URL: " . $webhookUrl . "<br>";
echo "Server Key: " . substr($_ENV['MIDTRANS_SERVER_KEY'] ?? 'Not set', 0, 10) . "...<br>";

// Sample notification data (you can modify this to test)
$sampleNotification = [
    'order_id' => 'INV202507181234-AB1C-1721234567-xyz123', // Replace with actual transaction ID
    'transaction_status' => 'settlement',
    'fraud_status' => 'accept',
    'payment_type' => 'credit_card',
    'transaction_time' => date('Y-m-d H:i:s'),
    'gross_amount' => '150000.00',
    'currency' => 'IDR'
];

echo "<h2>Test Webhook Notification:</h2>";
echo "<p>Sample notification data:</p>";
echo "<pre>" . json_encode($sampleNotification, JSON_PRETTY_PRINT) . "</pre>";

if (isset($_POST['send_webhook'])) {
    echo "<h3>Sending webhook notification...</h3>";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $webhookUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($sampleNotification));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen(json_encode($sampleNotification))
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "<div style='color: red;'>❌ CURL Error: " . $error . "</div>";
    } else {
        echo "<div style='color: " . ($httpCode == 200 ? 'green' : 'red') . ";'>";
        echo "HTTP Code: " . $httpCode . "<br>";
        echo "Response: " . $response . "<br>";
        echo "</div>";
    }
}

?>

<form method="POST">
    <h3>Custom Notification Test:</h3>
    <label>Transaction ID:</label><br>
    <input type="text" name="transaction_id" value="<?= $sampleNotification['order_id'] ?>" style="width: 100%; margin-bottom: 10px;"><br>
    
    <label>Transaction Status:</label><br>
    <select name="transaction_status" style="width: 100%; margin-bottom: 10px;">
        <option value="settlement" selected>settlement</option>
        <option value="capture">capture</option>
        <option value="pending">pending</option>
        <option value="expire">expire</option>
        <option value="cancel">cancel</option>
        <option value="deny">deny</option>
    </select><br>
    
    <label>Amount:</label><br>
    <input type="text" name="amount" value="<?= $sampleNotification['gross_amount'] ?>" style="width: 100%; margin-bottom: 10px;"><br>
    
    <button type="submit" name="send_webhook" style="padding: 10px 20px; background: #007cba; color: white; border: none; cursor: pointer;">
        Send Test Webhook
    </button>
</form>

<h3>Recent Logs:</h3>
<div style="background: #f5f5f5; padding: 10px; max-height: 300px; overflow-y: scroll;">
<?php
// Show recent Laravel logs related to Midtrans
$logFile = '../storage/logs/laravel.log';
if (file_exists($logFile)) {
    $lines = file($logFile);
    $midtransLines = array_filter($lines, function($line) {
        return strpos($line, 'Midtrans') !== false || strpos($line, 'payment') !== false;
    });
    
    $recentLines = array_slice($midtransLines, -20); // Last 20 lines
    foreach ($recentLines as $line) {
        echo htmlspecialchars($line) . "<br>";
    }
} else {
    echo "Log file not found: " . $logFile;
}
?>
</div>

<h3>Check Database:</h3>
<?php
try {
    $pdo = new PDO(
        "mysql:host=" . ($_ENV['DB_HOST'] ?? 'localhost') . ";dbname=" . ($_ENV['DB_DATABASE'] ?? 'test'),
        $_ENV['DB_USERNAME'] ?? 'root',
        $_ENV['DB_PASSWORD'] ?? ''
    );
    
    echo "✅ Database connection: OK<br>";
    
    // Check recent payments
    $stmt = $pdo->query("
        SELECT p.*, o.order_number 
        FROM payments p 
        JOIN orders o ON p.order_id = o.id 
        ORDER BY p.created_at DESC 
        LIMIT 5
    ");
    
    echo "<h4>Recent Payments:</h4>";
    echo "<table border='1' style='width: 100%; border-collapse: collapse;'>";
    echo "<tr><th>Order Number</th><th>Status</th><th>Transaction ID</th><th>Created</th></tr>";
    
    while ($row = $stmt->fetch()) {
        echo "<tr>";
        echo "<td>" . $row['order_number'] . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "<td>" . $row['transaction_id'] . "</td>";
        echo "<td>" . $row['created_at'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}
?>

<script>
// Auto refresh every 30 seconds to check for updates
setTimeout(function() {
    location.reload();
}, 30000);
</script>
