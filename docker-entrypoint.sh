#!/bin/bash
set -e

# Generate APP_KEY if not set or not in base64 format
if [ -z "$APP_KEY" ] || [[ "$APP_KEY" != base64:* ]]; then
    export APP_KEY=$(php artisan key:generate --show)
    echo "Generated APP_KEY: $APP_KEY"
fi

# Run migrations
php artisan migrate --force

# Cache config, routes, views
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache
exec apache2-foreground
