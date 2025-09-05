#!/bin/bash

echo "=== Whiteboard Startup ==="

# Create directories
echo "Creating directories..."
mkdir -p var/cache var/log data
chmod -R 777 var data 2>/dev/null || true

# Test PHP
echo "PHP Version: $(php --version | head -n1)"

# Start without database setup first (in case it fails)
if [ "$SKIP_DB_SETUP" = "true" ]; then
    echo "Skipping database setup"
    exec php -S 0.0.0.0:$PORT -t public
fi

# Try database setup
echo "Setting up database..."
set +e  # Don't exit on errors

php bin/console cache:clear --env=prod --no-debug
php bin/console doctrine:database:create --if-not-exists --env=prod --no-interaction
php bin/console doctrine:migrations:migrate --no-interaction --env=prod
php bin/console app:create-user diablesse@whiteboard.app diablesse123 --env=prod
php bin/console app:create-user mat@whiteboard.app mat123 --env=prod

set -e  # Resume exit on errors

echo "Starting server on port $PORT"
exec php -S 0.0.0.0:$PORT -t public
