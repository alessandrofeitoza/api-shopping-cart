# ─── Stage 1: Composer dependencies ─────────────────────────────────────────
FROM php:8.4-fpm AS composer-deps

WORKDIR /var/www

RUN apt-get update && apt-get install -y --no-install-recommends \
    unzip \
    git \
    curl \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_pgsql pgsql zip mbstring

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .
RUN composer dump-autoload --optimize --no-dev

# ─── Stage 2: Final image (PHP-FPM + Nginx + Supervisord) ────────────────────
FROM php:8.4-fpm

WORKDIR /var/www

# Install system dependencies, PHP extensions, Nginx and Supervisord
RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx \
    supervisor \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    zip \
    unzip \
    curl \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_pgsql pgsql zip mbstring exif
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install gd

# Copy application from build stage
COPY --from=composer-deps /var/www /var/www

# Copy Nginx config (fastcgi_pass points to 127.0.0.1:9000 — same container)
COPY docker/nginx/railway.conf /etc/nginx/conf.d/default.conf

# Remove the default Nginx site
RUN rm -f /etc/nginx/sites-enabled/default

# Copy Supervisord config
RUN mkdir -p /var/log/supervisor
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy and prepare entrypoint
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Fix storage and cache permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]
