<?php
// Backup index.php for Railway debugging

// Simple error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Try to start Symfony
try {
    // Check if vendor exists
    if (!file_exists(dirname(__DIR__).'/vendor/autoload_runtime.php')) {
        die('❌ Vendor directory missing. Run composer install.');
    }
    
    // Try to load Symfony
    require_once dirname(__DIR__).'/vendor/autoload_runtime.php';
    
    $handler = function (array $context) {
        return new App\Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
    };
    
    // Execute Symfony
    $handler($_ENV);
    
} catch (Exception $e) {
    // If Symfony fails, show error and fallback
    echo "<!DOCTYPE html><html><head><title>Symfony Error</title></head><body>";
    echo "<h1>❌ Symfony Error</h1>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
    echo "<h2>Debug Info:</h2>";
    echo "<p>PHP Version: " . PHP_VERSION . "</p>";
    echo "<p>Working Directory: " . getcwd() . "</p>";
    echo "<p>APP_ENV: " . ($_ENV['APP_ENV'] ?? 'not set') . "</p>";
    echo "<h2>Fallback:</h2>";
    echo '<p><a href="/test.php">Test basic PHP</a></p>';
    echo "</body></html>";
    exit;
}
?>
