<?php
// Test de la nouvelle URL SQLite
echo "<h1>Test de l'URL SQLite corrigée</h1>";

$oldUrl = "sqlite:///var/www/html/data/database.db";
$newUrl = "sqlite:/var/www/html/data/database.db";

echo "<h2>Test de l'ancienne URL (3 slashes)</h2>";
echo "URL: $oldUrl<br>";
try {
    $pdo1 = new PDO($oldUrl);
    echo "Connexion: <span style='color: green;'>SUCCESS</span><br>";
    $count1 = $pdo1->query("SELECT COUNT(*) FROM user")->fetchColumn();
    echo "Utilisateurs: $count1<br>";
} catch (Exception $e) {
    echo "Connexion: <span style='color: red;'>FAILED</span><br>";
    echo "Erreur: " . $e->getMessage() . "<br>";
}

echo "<h2>Test de la nouvelle URL (1 slash)</h2>";
echo "URL: $newUrl<br>";
try {
    $pdo2 = new PDO($newUrl);
    echo "Connexion: <span style='color: green;'>SUCCESS</span><br>";
    $count2 = $pdo2->query("SELECT COUNT(*) FROM user")->fetchColumn();
    echo "Utilisateurs: $count2<br>";
} catch (Exception $e) {
    echo "Connexion: <span style='color: red;'>FAILED</span><br>";
    echo "Erreur: " . $e->getMessage() . "<br>";
}

echo "<h2>Parsing des URLs</h2>";
echo "Ancienne URL parsed:<br>";
$parsed1 = parse_url($oldUrl);
echo "- Scheme: " . ($parsed1['scheme'] ?? 'none') . "<br>";
echo "- Host: " . ($parsed1['host'] ?? 'none') . "<br>";
echo "- Path: " . ($parsed1['path'] ?? 'none') . "<br>";

echo "<br>Nouvelle URL parsed:<br>";
$parsed2 = parse_url($newUrl);
echo "- Scheme: " . ($parsed2['scheme'] ?? 'none') . "<br>";
echo "- Host: " . ($parsed2['host'] ?? 'none') . "<br>";
echo "- Path: " . ($parsed2['path'] ?? 'none') . "<br>";
?>
