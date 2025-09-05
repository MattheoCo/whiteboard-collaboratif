#!/bin/bash

# Ensure directories exist with full permissions
mkdir -p var/cache var/log data
chmod -R 777 var data 2>/dev/null || true

# Clear cache for production
php bin/console cache:clear --env=prod --no-debug 2>/dev/null || true

# Create database and run migrations (silently, errors ignored)
php bin/console doctrine:database:create --if-not-exists --env=prod --no-interaction 2>/dev/null || true
php bin/console doctrine:migrations:migrate --no-interaction --env=prod 2>/dev/null || true

# Create users (silently, errors ignored)
php bin/console app:create-user diablesse@whiteboard.app diablesse123 --env=prod 2>/dev/null || true
php bin/console app:create-user mat@whiteboard.app mat123 --env=prod 2>/dev/null || true

echo "Starting server on port $PORT"
# Start the PHP server
php -S 0.0.0.0:$PORT -t public
