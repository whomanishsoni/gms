#!/usr/bin/env bash
# Exit immediately if any command fails
set -o errexit

echo "➤ 1/6 - Installing system dependencies..."
apt-get update && apt-get install -y \
    git \
    libzip-dev \
    zip \
    unzip \
    libpq-dev

echo "➤ 2/6 - Installing PHP extensions..."
docker-php-ext-install zip pdo pdo_pgsql

echo "➤ 3/6 - Installing Composer..."
curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

echo "➤ 4/6 - Installing application dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "➤ 5/6 - Setting up permissions..."
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

echo "➤ 6/6 - Optimizing Laravel..."
php artisan key:generate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Build completed successfully!"
