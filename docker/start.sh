#!/bin/sh

cd /var/www

echo "[start.sh] Starting api-shopping-cart..."

# ── .env setup ────────────────────────────────────────────────────────────────
if [ ! -f ".env" ]; then
    echo "[start.sh] .env not found, copying from .env.example"
    cp .env.example .env
fi

# Inject Railway / shell env vars into .env so artisan can read them
for var in APP_KEY APP_ENV APP_DEBUG APP_URL \
           PGHOST PGPORT PGDATABASE PGUSER PGPASSWORD \
           DB_CONNECTION DB_HOST DB_PORT DB_DATABASE DB_USERNAME DB_PASSWORD \
           SESSION_DRIVER CACHE_STORE; do
    eval "val=\$$var"
    if [ -n "$val" ]; then
        # Update existing key or append
        if grep -q "^${var}=" .env 2>/dev/null; then
            sed -i "s|^${var}=.*|${var}=${val}|" .env
        else
            echo "${var}=${val}" >> .env
        fi
    fi
done

# ── App key ───────────────────────────────────────────────────────────────────
if [ -z "$APP_KEY" ]; then
    echo "[start.sh] APP_KEY not set, generating..."
    php artisan key:generate --force
    # Re-export the generated key into the environment
    APP_KEY=$(grep "^APP_KEY=" .env | cut -d '=' -f2-)
    export APP_KEY
fi

echo "[start.sh] APP_KEY is set: $(echo "$APP_KEY" | cut -c1-10)..."

# ── Migrations ────────────────────────────────────────────────────────────────
echo "[start.sh] Running migrations..."
php artisan migrate --force || echo "[start.sh] WARNING: Migrations failed (database may not be ready yet). Continuing..."
echo "[start.sh] Migrations step complete."

# ── Cache config for faster boot ─────────────────────────────────────────────
echo "[start.sh] Caching configuration..."
php artisan config:cache
php artisan route:cache

# ── Healthcheck: verify the app bootstraps without errors ────────────────────
echo "[start.sh] Verifying application bootstrap..."
php artisan about --no-interaction > /dev/null 2>&1 && \
    echo "[start.sh] Bootstrap OK." || \
    echo "[start.sh] WARNING: 'artisan about' returned an error — check your config."

# ── Start server ──────────────────────────────────────────────────────────────
echo "[start.sh] Starting PHP built-in server on 0.0.0.0:80..."
exec php -S 0.0.0.0:80 -t /var/www/public /var/www/public/index.php
