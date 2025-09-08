#!/bin/bash

# Set proper permissions
chmod -R 777 /var/www/html/data /var/www/html/var 2>/dev/null || true
chown -R www-data:www-data /var/www/html/data /var/www/html/var 2>/dev/null || true

# Initialize database in the Symfony standard location
db_file="/var/www/html/var/data_prod.db"

# Ensure DATABASE_URL points to the expected SQLite file for Doctrine
export DATABASE_URL="sqlite:///$db_file"

echo "Using DATABASE_URL=$DATABASE_URL"

# Always attempt to create/update schema so required tables exist
echo "Ensuring database and schema are present..."
php /var/www/html/bin/console doctrine:database:create --if-not-exists --env=prod || true
php /var/www/html/bin/console doctrine:schema:update --force --env=prod || true

# Create default users if they do not exist (errors ignored)
echo "Ensuring default users exist..."
php /var/www/html/bin/console app:create-user diablesse@whiteboard.app diablesse123 --env=prod || true
php /var/www/html/bin/console app:create-user mat@whiteboard.app mat123 --env=prod || true

echo "Database initialization/check complete."

# Start Apache with proper port handling
sed -i "s/80/${PORT:-80}/" /etc/apache2/sites-available/000-default.conf
apache2-foreground
