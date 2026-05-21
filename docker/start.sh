#!/bin/sh
set -e

cd /var/www

# Create .env from .env.example if it does not exist
if [ ! -f ".env" ]; then
    echo ".env not found, copying from .env.example"
    cp .env.example .env
fi

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Run database migrations
php artisan migrate --force

# Start supervisord (manages nginx + php-fpm)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
