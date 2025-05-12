#!/usr/bin/env bash
set -o errexit

# Install PHP extensions (no sudo needed)
apt-get update && apt-get install -y php-cli php-mbstring php-xml php-zip unzip php-pgsql

# Install Composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Run Laravel commands
composer install --no-dev --no-interaction --prefer-dist
php artisan key:generate --force
php artisan storage:link
php artisan migrate --force

# Cache configuration for production
php artisan config:cache
php artisan route:cache
