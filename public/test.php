<?php
// Ultra-simple test for Railway
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Railway Debug Test</h1>";
echo "<p>PHP Version: " . PHP_VERSION . "</p>";
echo "<p>Working Directory: " . getcwd() . "</p>";
echo "<p>Server: " . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "</p>";

// Create directories
@mkdir('data', 0777, true);
@mkdir('var/cache', 0777, true);
@mkdir('var/log', 0777, true);

// Test database
try {
    $db = new PDO('sqlite:data/test.db');
    $db->exec('CREATE TABLE IF NOT EXISTS test (id INTEGER PRIMARY KEY, message TEXT)');
    $db->exec("INSERT INTO test (message) VALUES ('Hello from " . date('Y-m-d H:i:s') . "')");
    echo "<p>✅ Database: OK</p>";
} catch (Exception $e) {
    echo "<p>❌ Database Error: " . $e->getMessage() . "</p>";
}

// Test file permissions
if (is_writable('.')) {
    echo "<p>✅ Directory writable</p>";
} else {
    echo "<p>❌ Directory not writable</p>";
}

// List files
echo "<h2>Files in directory:</h2><ul>";
foreach (scandir('.') as $file) {
    if ($file !== '.' && $file !== '..') {
        echo "<li>$file</li>";
    }
}
echo "</ul>";

echo "<h2>Environment:</h2><ul>";
foreach ($_ENV as $key => $value) {
    if (strpos($key, 'DATABASE') !== false || strpos($key, 'APP') !== false || strpos($key, 'PORT') !== false) {
        echo "<li>$key = $value</li>";
    }
}
echo "</ul>";

?>
<!DOCTYPE html>
<html>
<head>
    <title>Railway Test</title>
</head>
<body>
    <h1>Basic PHP Test</h1>
    <p>If you see this, PHP is working!</p>
    <form method="post">
        <input type="submit" name="test" value="Test POST">
    </form>
    <?php if (isset($_POST['test'])): ?>
        <p>✅ POST request works!</p>
    <?php endif; ?>
</body>
</html>
