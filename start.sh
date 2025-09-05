#!/bin/bash

# Ensure directories exist
mkdir -p var/cache var/log data

# Clear cache for production
php bin/console cache:clear --env=prod --no-debug

# Create database if not exists
php bin/console doctrine:database:create --if-not-exists --env=prod --no-interaction

# Run migrations
php bin/console doctrine:migrations:migrate --no-interaction --env=prod

# Create users diablesse and mat
php bin/console app:create-user diablesse@whiteboard.app diablesse123 --env=prod 2>/dev/null || echo "User diablesse already exists"
php bin/console app:create-user mat@whiteboard.app mat123 --env=prod 2>/dev/null || echo "User mat already exists"

# Start the PHP server
php -S 0.0.0.0:$PORT -t public
