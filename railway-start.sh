#!/bin/bash

# Ensure bash is in strict mode
set -e

# Set environment variables explicitly
export APP_ENV="${APP_ENV:-prod}"
export APP_DEBUG="${APP_DEBUG:-false}"
export DATABASE_URL="${DATABASE_URL:-sqlite:///app/data/database.db}"

echo "=== Railway Startup Script ==="
echo "APP_ENV: $APP_ENV"
echo "APP_DEBUG: $APP_DEBUG"
echo "DATABASE_URL: $DATABASE_URL"
echo "PORT: $PORT"
echo "==========================="

# Create directories with proper permissions
mkdir -p data var/cache var/log app/data
chmod -R 755 data var app 2>/dev/null || true

# If a Symfony-produced production DB exists (var/data_prod.db), copy it to the
# default location used by the Railway startup default (app/data/database.db)
# so the application sees the populated database when DATABASE_URL is not set.
if [ -f "var/data_prod.db" ]; then
	echo "Found var/data_prod.db - copying to app/data/database.db"
	cp var/data_prod.db app/data/database.db || true
fi

# Start PHP server
echo "Starting PHP server..."
exec php -S "0.0.0.0:${PORT}" -t public
