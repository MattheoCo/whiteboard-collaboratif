<?php
// Debug de la configuration Symfony/Doctrine
echo "<h1>Symfony Configuration Debug</h1>";

try {
    chdir('/var/www/html');
    
    // Charger l'environnement Symfony
    require_once '/var/www/html/vendor/autoload.php';
    
    $dotenv = new Symfony\Component\Dotenv\Dotenv();
    $dotenv->loadEnv('/var/www/html/.env');
    
    echo "<h2>Environment Variables</h2>";
    echo "APP_ENV: " . ($_ENV['APP_ENV'] ?? 'not set') . "<br>";
    echo "DATABASE_URL from ENV: " . ($_ENV['DATABASE_URL'] ?? 'not set') . "<br>";
    echo "DATABASE_URL from SERVER: " . ($_SERVER['DATABASE_URL'] ?? 'not set') . "<br>";
    
    // Analyser l'URL de la base de données
    $databaseUrl = $_ENV['DATABASE_URL'] ?? $_SERVER['DATABASE_URL'] ?? '';
    echo "Final DATABASE_URL: $databaseUrl<br>";
    
    if ($databaseUrl) {
        $parsed = parse_url($databaseUrl);
        echo "Parsed URL:<br>";
        echo "- Scheme: " . ($parsed['scheme'] ?? 'none') . "<br>";
        echo "- Path: " . ($parsed['path'] ?? 'none') . "<br>";
        
        // Extraire le chemin réel du fichier
        if (isset($parsed['path'])) {
            $dbFile = $parsed['path'];
            if (strpos($dbFile, '///') === 0) {
                $dbFile = substr($dbFile, 3); // Remove ///
            }
            echo "- Database file path: $dbFile<br>";
            echo "- File exists: " . (file_exists($dbFile) ? 'Yes' : 'No') . "<br>";
            if (file_exists($dbFile)) {
                echo "- File readable: " . (is_readable($dbFile) ? 'Yes' : 'No') . "<br>";
                echo "- File writable: " . (is_writable($dbFile) ? 'Yes' : 'No') . "<br>";
                echo "- File size: " . filesize($dbFile) . " bytes<br>";
            }
        }
    }
    
    echo "<h2>Direct Database Test</h2>";
    $directPath = '/var/www/html/data/database.db';
    echo "Direct path: $directPath<br>";
    echo "Direct file exists: " . (file_exists($directPath) ? 'Yes' : 'No') . "<br>";
    if (file_exists($directPath)) {
        echo "Direct file size: " . filesize($directPath) . " bytes<br>";
        echo "Direct file readable: " . (is_readable($directPath) ? 'Yes' : 'No') . "<br>";
        echo "Direct file writable: " . (is_writable($directPath) ? 'Yes' : 'No') . "<br>";
    }
    
    echo "<h2>Test SQLite Connection</h2>";
    
    // Test avec l'URL Symfony
    try {
        if ($databaseUrl && strpos($databaseUrl, 'sqlite:') === 0) {
            $dsn = str_replace('sqlite:///', 'sqlite:', $databaseUrl);
            $pdo1 = new PDO($dsn);
            echo "Symfony URL connection: SUCCESS<br>";
            $count1 = $pdo1->query("SELECT COUNT(*) FROM user")->fetchColumn();
            echo "Users via Symfony URL: $count1<br>";
        }
    } catch (Exception $e) {
        echo "Symfony URL connection failed: " . $e->getMessage() . "<br>";
    }
    
    // Test avec le chemin direct
    try {
        $pdo2 = new PDO("sqlite:$directPath");
        echo "Direct path connection: SUCCESS<br>";
        $count2 = $pdo2->query("SELECT COUNT(*) FROM user")->fetchColumn();
        echo "Users via direct path: $count2<br>";
    } catch (Exception $e) {
        echo "Direct path connection failed: " . $e->getMessage() . "<br>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
    echo "Stack trace:<br><pre>" . $e->getTraceAsString() . "</pre>";
}
?>
