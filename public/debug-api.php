<?php
// Debug des API Symfony
echo "<h1>Debug des API Symfony</h1>";

try {
    require_once dirname(__DIR__) . '/vendor/autoload.php';
    
    $_ENV['APP_ENV'] = 'prod';
    $_ENV['APP_DEBUG'] = 'false';
    
    $dotenv = new Symfony\Component\Dotenv\Dotenv();
    $dotenv->loadEnv(dirname(__DIR__) . '/.env');
    
    // Créer le kernel Symfony
    $kernel = new App\Kernel('prod', false);
    $kernel->boot();
    
    $container = $kernel->getContainer();
    $entityManager = $container->get('doctrine.orm.entity_manager');
    
    echo "<h2>Test des repositories</h2>";
    
    // Test StickyNote Repository
    try {
        $stickyNoteRepo = $entityManager->getRepository('App\Entity\StickyNote');
        $stickyNotes = $stickyNoteRepo->findAll();
        echo "✅ StickyNote Repository: " . count($stickyNotes) . " notes<br>";
    } catch (Exception $e) {
        echo "❌ StickyNote Repository: " . $e->getMessage() . "<br>";
    }
    
    // Test Stroke Repository
    try {
        $strokeRepo = $entityManager->getRepository('App\Entity\Stroke');
        $strokes = $strokeRepo->findAll();
        echo "✅ Stroke Repository: " . count($strokes) . " strokes<br>";
    } catch (Exception $e) {
        echo "❌ Stroke Repository: " . $e->getMessage() . "<br>";
    }
    
    // Test Event Repository
    try {
        $eventRepo = $entityManager->getRepository('App\Entity\Event');
        $events = $eventRepo->findAll();
        echo "✅ Event Repository: " . count($events) . " events<br>";
    } catch (Exception $e) {
        echo "❌ Event Repository: " . $e->getMessage() . "<br>";
    }
    
    // Test MapMarker Repository
    try {
        $markerRepo = $entityManager->getRepository('App\Entity\MapMarker');
        $markers = $markerRepo->findAll();
        echo "✅ MapMarker Repository: " . count($markers) . " markers<br>";
    } catch (Exception $e) {
        echo "❌ MapMarker Repository: " . $e->getMessage() . "<br>";
    }
    
    // Test Image Repository
    try {
        $imageRepo = $entityManager->getRepository('App\Entity\Image');
        $images = $imageRepo->findAll();
        echo "✅ Image Repository: " . count($images) . " images<br>";
    } catch (Exception $e) {
        echo "❌ Image Repository: " . $e->getMessage() . "<br>";
    }
    
    echo "<h2>Test de création d'entités</h2>";
    
    // Test de création d'un sticky note
    try {
        $stickyNote = new App\Entity\StickyNote();
        $stickyNote->setText('Test note');
        $stickyNote->setX(100);
        $stickyNote->setY(100);
        $stickyNote->setColor('#ffeb3b');
        $stickyNote->setTimestamp(new DateTime());
        
        $entityManager->persist($stickyNote);
        $entityManager->flush();
        
        echo "✅ StickyNote creation: SUCCESS<br>";
        
        // Supprimer le test
        $entityManager->remove($stickyNote);
        $entityManager->flush();
        
    } catch (Exception $e) {
        echo "❌ StickyNote creation: " . $e->getMessage() . "<br>";
    }
    
    echo "<h2>Structure de la base de données</h2>";
    
    $connection = $entityManager->getConnection();
    $tables = $connection->executeQuery("SELECT name FROM sqlite_master WHERE type='table'")->fetchFirstColumn();
    echo "Tables disponibles: " . implode(', ', $tables) . "<br>";
    
    foreach (['sticky_note', 'stroke', 'event', 'map_marker', 'image'] as $table) {
        if (in_array($table, $tables)) {
            try {
                $count = $connection->executeQuery("SELECT COUNT(*) FROM $table")->fetchOne();
                echo "- $table: $count enregistrements<br>";
            } catch (Exception $e) {
                echo "- $table: Erreur - " . $e->getMessage() . "<br>";
            }
        } else {
            echo "- $table: ❌ Table manquante<br>";
        }
    }
    
} catch (Exception $e) {
    echo "<span style='color: red;'>Erreur: " . $e->getMessage() . "</span><br>";
    echo "Stack trace:<br><pre>" . $e->getTraceAsString() . "</pre>";
}
?>
