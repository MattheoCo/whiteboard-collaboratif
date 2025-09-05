#!/bin/bash

# Ensure directories exist
mkdir -p var/cache var/log data

# Clear cache for production
php bin/console cache:clear --env=prod --no-debug

# Create database if not exists
php bin/console doctrine:database:create --if-not-exists --env=prod --no-interaction

# Run migrations
php bin/console doctrine:migrations:migrate --no-interaction --env=prod

# Create default admin user if not exists
php bin/console app:create-user admin@whiteboard.app admin123 --env=prod 2>/dev/null || echo "Admin user already exists"

# Start the PHP server
php -S 0.0.0.0:$PORT -t public
