<?php
// Debug de la base de données
echo "<h1>Debug Database</h1>";

try {
    echo "PHP Version: " . phpversion() . "<br>";
    echo "SQLite Extension: " . (extension_loaded('sqlite3') ? 'Loaded' : 'Not loaded') . "<br>";
    echo "PDO SQLite: " . (extension_loaded('pdo_sqlite') ? 'Loaded' : 'Not loaded') . "<br>";
    
    $dbPath = '/var/www/html/data/database.db';
    echo "Database path: $dbPath<br>";
    echo "Database exists: " . (file_exists($dbPath) ? 'Yes' : 'No') . "<br>";
    
    if (file_exists($dbPath)) {
        echo "Database size: " . filesize($dbPath) . " bytes<br>";
        echo "Database readable: " . (is_readable($dbPath) ? 'Yes' : 'No') . "<br>";
        echo "Database writable: " . (is_writable($dbPath) ? 'Yes' : 'No') . "<br>";
    }
    
    // Test de connexion SQLite directe
    try {
        $pdo = new PDO("sqlite:$dbPath");
        echo "Direct SQLite connection: SUCCESS<br>";
        
        // Test de création de table
        $pdo->exec("CREATE TABLE IF NOT EXISTS test (id INTEGER PRIMARY KEY, name TEXT)");
        echo "Table creation: SUCCESS<br>";
        
        // Test d'insertion
        $pdo->exec("INSERT OR IGNORE INTO test (name) VALUES ('test')");
        echo "Insert test: SUCCESS<br>";
        
        $result = $pdo->query("SELECT COUNT(*) FROM test")->fetchColumn();
        echo "Records in test table: $result<br>";
        
    } catch (Exception $e) {
        echo "SQLite connection error: " . $e->getMessage() . "<br>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";
echo "Environment variables:<br>";
foreach ($_ENV as $key => $value) {
    if (strpos($key, 'APP_') === 0 || strpos($key, 'DATABASE_') === 0) {
        echo "$key = $value<br>";
    }
}
?>
