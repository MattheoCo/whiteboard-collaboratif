<?php
// Test simple pour Railway
echo "PHP fonctionne !<br>";
echo "APP_ENV: " . ($_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? 'non défini') . "<br>";
echo "APP_DEBUG: " . ($_ENV['APP_DEBUG'] ?? $_SERVER['APP_DEBUG'] ?? 'non défini') . "<br>";
echo "PORT: " . ($_ENV['PORT'] ?? $_SERVER['PORT'] ?? 'non défini') . "<br>";
echo "Date: " . date('Y-m-d H:i:s') . "<br>";
phpinfo();
