<?php
// Script pour migrer les données vers la base de données Symfony
echo "<h1>Migration des données vers Symfony</h1>";

try {
    $sourceDb = '/var/www/html/data/database.db';
    $targetDb = '/var/www/html/var/data_prod.db';
    
    echo "Source: $sourceDb<br>";
    echo "Target: $targetDb<br>";
    
    // Vérifier que la source existe
    if (!file_exists($sourceDb)) {
        throw new Exception("Base de données source introuvable");
    }
    
    echo "Source database size: " . filesize($sourceDb) . " bytes<br>";
    
    // Connexion à la source
    $sourcePdo = new PDO("sqlite:$sourceDb");
    $sourcePdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Connexion ou création de la cible
    $targetPdo = new PDO("sqlite:$targetDb");
    $targetPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to both databases<br>";
    
    // Obtenir la structure de la source
    $tables = $sourcePdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name != 'sqlite_sequence'")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables to migrate: " . implode(', ', $tables) . "<br>";
    
    foreach ($tables as $table) {
        echo "<h3>Migrating table: $table</h3>";
        
        // Obtenir la structure de la table
        $createStmt = $sourcePdo->query("SELECT sql FROM sqlite_master WHERE name='$table'")->fetchColumn();
        echo "Creating table...<br>";
        $targetPdo->exec("DROP TABLE IF EXISTS $table");
        $targetPdo->exec($createStmt);
        
        // Copier les données
        $data = $sourcePdo->query("SELECT * FROM $table")->fetchAll(PDO::FETCH_ASSOC);
        echo "Copying " . count($data) . " records...<br>";
        
        if (count($data) > 0) {
            $columns = array_keys($data[0]);
            $placeholders = str_repeat('?,', count($columns) - 1) . '?';
            $insertSql = "INSERT INTO $table (" . implode(',', $columns) . ") VALUES ($placeholders)";
            
            $stmt = $targetPdo->prepare($insertSql);
            
            foreach ($data as $row) {
                $stmt->execute(array_values($row));
            }
        }
        
        echo "✅ Table $table migrated successfully<br>";
    }
    
    echo "<h2>Migration completed!</h2>";
    
    // Vérifier la migration
    $targetSize = filesize($targetDb);
    echo "Target database size after migration: $targetSize bytes<br>";
    
    // Vérifier les utilisateurs
    $userCount = $targetPdo->query("SELECT COUNT(*) FROM user")->fetchColumn();
    echo "Users in target database: $userCount<br>";
    
    $users = $targetPdo->query("SELECT email FROM user")->fetchAll(PDO::FETCH_COLUMN);
    echo "User emails: " . implode(', ', $users) . "<br>";
    
    echo "<br><strong style='color: green;'>✅ Migration réussie ! Symfony peut maintenant accéder aux données.</strong><br>";
    echo "<a href='/login' style='display: inline-block; padding: 10px 20px; background: #007cba; color: white; text-decoration: none; border-radius: 5px; margin-top: 10px;'>Tester la connexion Symfony</a>";
    
} catch (Exception $e) {
    echo "<span style='color: red;'>Erreur: " . $e->getMessage() . "</span><br>";
    echo "Stack trace:<br><pre>" . $e->getTraceAsString() . "</pre>";
}
?>
