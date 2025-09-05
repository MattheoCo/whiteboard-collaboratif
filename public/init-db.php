<?php
// Script pour initialiser complètement la base de données Symfony
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Symfony Database Initialization</h1>";

try {
    // Changer le répertoire de travail
    chdir('/var/www/html');
    
    echo "Current directory: " . getcwd() . "<br>";
    echo "Checking if bin/console exists: " . (file_exists('bin/console') ? 'Yes' : 'No') . "<br>";
    
    // Initialiser l'environnement
    putenv('APP_ENV=prod');
    putenv('APP_DEBUG=false');
    putenv('DATABASE_URL=sqlite:///var/www/html/data/database.db');
    
    echo "Environment variables set<br>";
    
    // Exécuter les commandes Doctrine
    $commands = [
        'php bin/console doctrine:database:create --if-not-exists --env=prod',
        'php bin/console doctrine:schema:update --force --env=prod',
        'php bin/console cache:clear --env=prod'
    ];
    
    foreach ($commands as $command) {
        echo "<h3>Executing: $command</h3>";
        
        $output = [];
        $return_var = 0;
        
        exec("$command 2>&1", $output, $return_var);
        
        echo "Exit code: $return_var<br>";
        echo "Output:<br><pre>" . implode("\n", $output) . "</pre>";
        
        if ($return_var !== 0) {
            echo "<span style='color: red;'>Command failed!</span><br>";
        } else {
            echo "<span style='color: green;'>Command successful!</span><br>";
        }
        echo "<hr>";
    }
    
    // Vérifier la structure de la base de données
    echo "<h3>Database structure check</h3>";
    $dbPath = '/var/www/html/data/database.db';
    
    if (file_exists($dbPath)) {
        $pdo = new PDO("sqlite:$dbPath");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
        echo "Tables in database: " . implode(', ', $tables) . "<br>";
        
        if (in_array('user', $tables)) {
            $userCount = $pdo->query("SELECT COUNT(*) FROM user")->fetchColumn();
            echo "Users in database: $userCount<br>";
        }
    }
    
} catch (Exception $e) {
    echo "<span style='color: red;'>Error: " . $e->getMessage() . "</span><br>";
    echo "Stack trace:<br><pre>" . $e->getTraceAsString() . "</pre>";
}
?>
