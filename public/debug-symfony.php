<?php
// Script de debug avancé pour Symfony
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Symfony Error Debug</h1>";

try {
    // Simuler une requête Symfony
    chdir('/var/www/html');
    
    // Charger l'autoloader
    require_once '/var/www/html/vendor/autoload.php';
    
    echo "Autoloader loaded<br>";
    
    // Initialiser les variables d'environnement
    $_ENV['APP_ENV'] = 'prod';
    $_ENV['APP_DEBUG'] = 'false';
    $_ENV['DATABASE_URL'] = 'sqlite:///var/www/html/data/database.db';
    
    echo "Environment variables set<br>";
    
    // Essayer de créer le kernel Symfony
    require_once '/var/www/html/src/Kernel.php';
    
    $kernel = new App\Kernel('prod', false);
    echo "Kernel created successfully<br>";
    
    $kernel->boot();
    echo "Kernel booted successfully<br>";
    
    // Essayer d'accéder à la base de données via Doctrine
    $container = $kernel->getContainer();
    echo "Container retrieved<br>";
    
    $entityManager = $container->get('doctrine.orm.entity_manager');
    echo "Entity manager retrieved<br>";
    
    // Tester une requête simple
    $connection = $entityManager->getConnection();
    echo "Database connection retrieved<br>";
    
    $result = $connection->executeQuery('SELECT 1 as test')->fetchAssociative();
    echo "Database query successful: " . json_encode($result) . "<br>";
    
    // Essayer de récupérer les utilisateurs
    $userRepository = $entityManager->getRepository('App\Entity\User');
    echo "User repository retrieved<br>";
    
    $users = $userRepository->findAll();
    echo "Users found: " . count($users) . "<br>";
    
    foreach ($users as $user) {
        echo "User: " . $user->getEmail() . "<br>";
    }
    
    echo "<br><strong style='color: green;'>All Symfony components working correctly!</strong><br>";
    
} catch (Exception $e) {
    echo "<span style='color: red;'>Error: " . $e->getMessage() . "</span><br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
    echo "Stack trace:<br><pre>" . $e->getTraceAsString() . "</pre>";
} catch (Error $e) {
    echo "<span style='color: red;'>Fatal Error: " . $e->getMessage() . "</span><br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
    echo "Stack trace:<br><pre>" . $e->getTraceAsString() . "</pre>";
}
?>
