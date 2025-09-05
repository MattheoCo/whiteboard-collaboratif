<?php
// Simple debug script for Railway

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "PHP Debug Info:\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Working Directory: " . getcwd() . "\n";
echo "Extensions: " . implode(', ', get_loaded_extensions()) . "\n";

// Test database connection
try {
    $pdo = new PDO('sqlite:data/database.db');
    echo "Database: OK\n";
} catch (Exception $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
}

// Test basic Symfony
try {
    require_once 'vendor/autoload.php';
    echo "Autoload: OK\n";
} catch (Exception $e) {
    echo "Autoload Error: " . $e->getMessage() . "\n";
}

echo "Debug completed.\n";
?>
