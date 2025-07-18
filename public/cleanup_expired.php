<?php

require_once '../vendor/autoload.php';

// Load environment variables
if (file_exists('../.env')) {
    $env = parse_ini_file('../.env');
    foreach ($env as $key => $value) {
        $_ENV[$key] = $value;
    }
}

try {
    $pdo = new PDO(
        "mysql:host=" . ($_ENV['DB_HOST'] ?? 'localhost') . ";dbname=" . ($_ENV['DB_DATABASE'] ?? 'test'),
        $_ENV['DB_USERNAME'] ?? 'root',
        $_ENV['DB_PASSWORD'] ?? ''
    );
    
    // Update expired pending payments to failed
    $stmt = $pdo->prepare("
        UPDATE payments 
        SET status = 'failed', updated_at = NOW()
        WHERE status = 'pending' 
        AND snap_token_expires_at < NOW()
    ");
    
    $stmt->execute();
    $affectedRows = $stmt->rowCount();
    
    echo "Cleaned up {$affectedRows} expired payments successfully!";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

?>
