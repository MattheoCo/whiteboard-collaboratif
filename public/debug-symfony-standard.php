<?php
// Debug de la configuration Symfony standard
echo "<h1>Debug Configuration Symfony Standard</h1>";

try {
    chdir('/var/www/html');
    require_once '/var/www/html/vendor/autoload.php';
    
    // Simuler l'environnement Symfony
    $_ENV['APP_ENV'] = 'prod';
    $_ENV['APP_DEBUG'] = 'false';
    
    $dotenv = new Symfony\Component\Dotenv\Dotenv();
    $dotenv->loadEnv('/var/www/html/.env');
    
    echo "<h2>Configuration Symfony</h2>";
    echo "APP_ENV: " . $_ENV['APP_ENV'] . "<br>";
    echo "PROJECT_DIR: /var/www/html<br>";
    
    // Calculer le chemin de la base de données selon Symfony
    $projectDir = '/var/www/html';
    $environment = $_ENV['APP_ENV'];
    $expectedDbPath = "$projectDir/var/data_$environment.db";
    
    echo "Expected database path: $expectedDbPath<br>";
    echo "Database exists: " . (file_exists($expectedDbPath) ? 'Yes' : 'No') . "<br>";
    
    if (file_exists($expectedDbPath)) {
        echo "Database size: " . filesize($expectedDbPath) . " bytes<br>";
        echo "Database readable: " . (is_readable($expectedDbPath) ? 'Yes' : 'No') . "<br>";
        echo "Database writable: " . (is_writable($expectedDbPath) ? 'Yes' : 'No') . "<br>";
    }
    
    echo "<h2>Test de connexion Doctrine</h2>";
    
    // Test direct avec le chemin attendu
    try {
        $pdo = new PDO("sqlite:$expectedDbPath");
        echo "Direct connection to expected path: <span style='color: green;'>SUCCESS</span><br>";
        
        // Vérifier les tables
        $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
        echo "Tables: " . implode(', ', $tables) . "<br>";
        
        if (in_array('user', $tables)) {
            $userCount = $pdo->query("SELECT COUNT(*) FROM user")->fetchColumn();
            echo "Users: $userCount<br>";
        }
        
    } catch (Exception $e) {
        echo "Direct connection failed: <span style='color: red;'>" . $e->getMessage() . "</span><br>";
    }
    
    echo "<h2>Répertoires et permissions</h2>";
    echo "var/ directory exists: " . (is_dir('/var/www/html/var') ? 'Yes' : 'No') . "<br>";
    echo "var/ directory writable: " . (is_writable('/var/www/html/var') ? 'Yes' : 'No') . "<br>";
    
    // Lister le contenu du répertoire var
    if (is_dir('/var/www/html/var')) {
        $files = scandir('/var/www/html/var');
        echo "Files in var/: " . implode(', ', array_filter($files, function($f) { return $f !== '.' && $f !== '..'; })) . "<br>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
    echo "Stack trace:<br><pre>" . $e->getTraceAsString() . "</pre>";
}
?>
