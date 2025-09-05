<?php
// Test final de Symfony après migration
echo "<h1>Test Final Symfony Post-Migration</h1>";

try {
    chdir('/var/www/html');
    require_once '/var/www/html/vendor/autoload.php';
    
    // Simuler l'environnement de production
    $_ENV['APP_ENV'] = 'prod';
    $_ENV['APP_DEBUG'] = 'false';
    
    $dotenv = new Symfony\Component\Dotenv\Dotenv();
    $dotenv->loadEnv('/var/www/html/.env');
    
    echo "Environment loaded<br>";
    
    // Créer le kernel Symfony
    $kernel = new App\Kernel('prod', false);
    $kernel->boot();
    
    echo "Symfony kernel booted<br>";
    
    // Récupérer le container
    $container = $kernel->getContainer();
    $entityManager = $container->get('doctrine.orm.entity_manager');
    
    echo "Entity manager retrieved<br>";
    
    // Test de connexion à la base de données
    $connection = $entityManager->getConnection();
    echo "Database connection established<br>";
    
    // Test d'une requête simple
    $result = $connection->executeQuery('SELECT COUNT(*) as count FROM user')->fetchAssociative();
    echo "✅ Database query successful!<br>";
    echo "Users in database: " . $result['count'] . "<br>";
    
    // Test du repository User
    $userRepository = $entityManager->getRepository('App\Entity\User');
    $users = $userRepository->findAll();
    
    echo "✅ User repository working!<br>";
    echo "Users found via Doctrine: " . count($users) . "<br>";
    
    foreach ($users as $user) {
        echo "- " . $user->getEmail() . "<br>";
    }
    
    echo "<br><h2 style='color: green;'>🎉 Symfony fonctionne parfaitement !</h2>";
    echo "<p>L'application est maintenant prête. Vous pouvez vous connecter avec :</p>";
    echo "<ul>";
    echo "<li><strong>diablesse@whiteboard.app</strong> / diablesse123</li>";
    echo "<li><strong>mat@whiteboard.app</strong> / mat123</li>";
    echo "</ul>";
    
    echo "<a href='/login' style='display: inline-block; padding: 15px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 8px; font-weight: bold; margin: 20px 0;'>🚀 Accéder au Whiteboard</a>";
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ Erreur Symfony</h2>";
    echo "<p><strong>Message:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Fichier:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Ligne:</strong> " . $e->getLine() . "</p>";
    
    // Suggestions de dépannage
    echo "<h3>🔧 Dépannage suggéré :</h3>";
    echo "<ol>";
    echo "<li><a href='/migrate-data.php'>Exécuter la migration des données</a></li>";
    echo "<li><a href='/debug-config.php'>Vérifier la configuration</a></li>";
    echo "<li><a href='/setup-db.php'>Recréer la base de données</a></li>";
    echo "</ol>";
}
?>
