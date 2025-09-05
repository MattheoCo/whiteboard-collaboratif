<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    // Ensure environment variables are set with defaults
    $env = $context['APP_ENV'] ?? $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? 'prod';
    $debug = $context['APP_DEBUG'] ?? $_ENV['APP_DEBUG'] ?? $_SERVER['APP_DEBUG'] ?? false;
    
    // Convert debug to boolean if it's a string
    if (is_string($debug)) {
        $debug = filter_var($debug, FILTER_VALIDATE_BOOLEAN);
    }
    
    return new Kernel($env, (bool) $debug);
};
