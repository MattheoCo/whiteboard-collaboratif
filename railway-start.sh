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
mkdir -p data var/cache var/log
chmod -R 755 data var 2>/dev/null || true

# Start PHP server
echo "Starting PHP server..."
exec php -S "0.0.0.0:${PORT}" -t public
