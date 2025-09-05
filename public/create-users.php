<?php
// Script pour créer les utilisateurs par défaut
require_once '/var/www/html/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->loadEnv('/var/www/html/.env');

$dbPath = '/var/www/html/data/database.db';

try {
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Créer la table users si elle n'existe pas
    $pdo->exec("CREATE TABLE IF NOT EXISTS user (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email VARCHAR(180) NOT NULL UNIQUE,
        roles TEXT NOT NULL,
        password VARCHAR(255) NOT NULL
    )");
    
    // Créer les utilisateurs
    $users = [
        ['email' => 'diablesse@whiteboard.app', 'password' => 'diablesse123'],
        ['email' => 'mat@whiteboard.app', 'password' => 'mat123']
    ];
    
    foreach ($users as $user) {
        $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);
        $roles = '["ROLE_USER"]';
        
        $stmt = $pdo->prepare("INSERT OR REPLACE INTO user (email, roles, password) VALUES (?, ?, ?)");
        $stmt->execute([$user['email'], $roles, $hashedPassword]);
        
        echo "User " . $user['email'] . " created successfully<br>";
    }
    
    echo "All users created successfully!<br>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}
?>
