<?php
// Script direct pour créer la structure complète de la base de données
echo "<h1>Direct Database Setup</h1>";

$dbPath = '/var/www/html/data/database.db';

try {
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database<br>";
    
    // Créer toutes les tables nécessaires
    $sql = "
    -- Table user
    CREATE TABLE IF NOT EXISTS user (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email VARCHAR(180) NOT NULL UNIQUE,
        roles TEXT NOT NULL,
        password VARCHAR(255) NOT NULL
    );
    
    -- Table sticky_note
    CREATE TABLE IF NOT EXISTS sticky_note (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        content TEXT NOT NULL,
        x REAL NOT NULL,
        y REAL NOT NULL,
        color VARCHAR(50) NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        board_id VARCHAR(255) NOT NULL DEFAULT 'default'
    );
    
    -- Table stroke
    CREATE TABLE IF NOT EXISTS stroke (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        path TEXT NOT NULL,
        color VARCHAR(50) NOT NULL,
        width REAL NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        board_id VARCHAR(255) NOT NULL DEFAULT 'default'
    );
    
    -- Table event
    CREATE TABLE IF NOT EXISTS event (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        start_date DATETIME NOT NULL,
        end_date DATETIME,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        board_id VARCHAR(255) NOT NULL DEFAULT 'default'
    );
    
    -- Table map_marker
    CREATE TABLE IF NOT EXISTS map_marker (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        latitude REAL NOT NULL,
        longitude REAL NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        board_id VARCHAR(255) NOT NULL DEFAULT 'default'
    );
    
    -- Table image
    CREATE TABLE IF NOT EXISTS image (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        filename VARCHAR(255) NOT NULL,
        x REAL NOT NULL,
        y REAL NOT NULL,
        width REAL,
        height REAL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        board_id VARCHAR(255) NOT NULL DEFAULT 'default'
    );
    ";
    
    $pdo->exec($sql);
    echo "All tables created successfully<br>";
    
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
        
        echo "User " . $user['email'] . " created<br>";
    }
    
    // Vérifier la taille de la base de données
    clearstatcache();
    $size = filesize($dbPath);
    echo "Database size after setup: $size bytes<br>";
    
    // Lister toutes les tables
    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables created: " . implode(', ', $tables) . "<br>";
    
    // Compter les utilisateurs
    $userCount = $pdo->query("SELECT COUNT(*) FROM user")->fetchColumn();
    echo "Total users: $userCount<br>";
    
    echo "<br><strong style='color: green;'>Database setup completed successfully!</strong><br>";
    echo "<a href='/login'>Try login now</a>";
    
} catch (Exception $e) {
    echo "<span style='color: red;'>Error: " . $e->getMessage() . "</span><br>";
    echo "Stack trace:<br><pre>" . $e->getTraceAsString() . "</pre>";
}
?>
