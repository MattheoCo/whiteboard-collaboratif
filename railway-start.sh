#!/bin/bash

# Set environment variables for Railway
export APP_ENV=prod
export APP_DEBUG=false
export DATABASE_URL="sqlite:///app/data/database.db"

# Create directories
mkdir -p data var/cache var/log
chmod -R 777 data var 2>/dev/null || true

echo "Environment: APP_ENV=$APP_ENV"
echo "Database: $DATABASE_URL" 
echo "Starting server on port $PORT"

# Start PHP server
php -S 0.0.0.0:$PORT -t public
