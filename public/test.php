<?php
// Temporary test file to verify nginx + php-fpm connection
echo "<h1>SISTECH Debug</h1>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>DB_CONNECTION: " . ($_ENV['DB_CONNECTION'] ?? 'NOT SET') . "</p>";
echo "<p>DB_HOST: " . ($_ENV['DB_HOST'] ?? 'NOT SET') . "</p>";
echo "<p>DB_DATABASE: " . ($_ENV['DB_DATABASE'] ?? 'NOT SET') . "</p>";
echo "<p>APP_DEBUG: " . ($_ENV['APP_DEBUG'] ?? 'NOT SET') . "</p>";
echo "<p>APP_KEY: " . (($_ENV['APP_KEY'] ?? '') ? 'SET' : 'NOT SET') . "</p>";
echo "<p>APP_URL: " . ($_ENV['APP_URL'] ?? 'NOT SET') . "</p>";

// Try connecting to database
try {
    $conn = new PDO(
        'pgsql:host=' . ($_ENV['DB_HOST'] ?? 'localhost') . ';port=' . ($_ENV['DB_PORT'] ?? '5432') . ';dbname=' . ($_ENV['DB_DATABASE'] ?? ''),
        $_ENV['DB_USERNAME'] ?? '',
        $_ENV['DB_PASSWORD'] ?? ''
    );
    echo "<p style='color:green'>Database: CONNECTED</p>";
} catch (Exception $e) {
    echo "<p style='color:red'>Database: FAILED - " . $e->getMessage() . "</p>";
}
