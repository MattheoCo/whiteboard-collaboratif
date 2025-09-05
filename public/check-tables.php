<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(dirname(__DIR__) . '/.env');

$databasePath = dirname(__DIR__) . '/var/data_prod.db';

echo "Database file: $databasePath\n";
echo "File exists: " . (file_exists($databasePath) ? 'Yes' : 'No') . "\n";

if (file_exists($databasePath)) {
    try {
        $pdo = new PDO("sqlite:$databasePath");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "\n=== Existing Tables ===\n";
        $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        foreach ($tables as $table) {
            echo "- $table\n";
        }
        
        echo "\n=== Table Schemas ===\n";
        foreach ($tables as $table) {
            echo "\n--- $table ---\n";
            $stmt = $pdo->query("PRAGMA table_info($table)");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($columns as $column) {
                echo "  {$column['name']} ({$column['type']}) " . 
                     ($column['notnull'] ? 'NOT NULL' : 'NULL') . 
                     ($column['pk'] ? ' PRIMARY KEY' : '') . "\n";
            }
        }
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "Database file does not exist!\n";
}
?>
