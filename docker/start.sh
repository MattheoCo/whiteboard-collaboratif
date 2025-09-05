#!/bin/bash

# Set proper permissions
chmod -R 777 /var/www/html/data /var/www/html/var 2>/dev/null || true
chown -R www-data:www-data /var/www/html/data /var/www/html/var 2>/dev/null || true

# Initialize database in the Symfony standard location
db_file="/var/www/html/var/data_prod.db"

if [ ! -f "$db_file" ] || [ ! -s "$db_file" ]; then
    echo "Initializing Symfony database at $db_file..."
    
    # Create database using Doctrine
    php /var/www/html/bin/console doctrine:database:create --if-not-exists --env=prod
    php /var/www/html/bin/console doctrine:schema:update --force --env=prod
    
    # Create default users
    echo "Creating default users..."
    php /var/www/html/bin/console app:create-user diablesse@whiteboard.app diablesse123 --env=prod || true
    php /var/www/html/bin/console app:create-user mat@whiteboard.app mat123 --env=prod || true
    
    echo "Database initialized successfully!"
fi

# Start Apache with proper port handling
sed -i "s/80/${PORT:-80}/" /etc/apache2/sites-available/000-default.conf
apache2-foreground
