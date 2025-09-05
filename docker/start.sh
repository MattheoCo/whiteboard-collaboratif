#!/bin/bash

# Set proper permissions
chmod -R 777 /var/www/html/data /var/www/html/var 2>/dev/null || true
chown -R www-data:www-data /var/www/html/data /var/www/html/var 2>/dev/null || true

# Initialize database if it doesn't exist
if [ ! -f /var/www/html/data/database.db ] || [ ! -s /var/www/html/data/database.db ]; then
    echo "Initializing database..."
    touch /var/www/html/data/database.db
    chmod 666 /var/www/html/data/database.db
    chown www-data:www-data /var/www/html/data/database.db
    
    # Create database schema
    php /var/www/html/bin/console doctrine:schema:update --force --env=prod || true
fi

# Start Apache with proper port handling
sed -i "s/80/${PORT:-80}/" /etc/apache2/sites-available/000-default.conf
apache2-foreground
