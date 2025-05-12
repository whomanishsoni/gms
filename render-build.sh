#!/usr/bin/env bash
set -o errexit

# Install PHP and dependencies
sudo apt-get update
sudo apt-get install -y php-cli php-mbstring php-xml php-zip unzip

# Install Composer
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

# Install PostgreSQL client if needed
sudo apt-get install -y php-pgsql

# Run composer and artisan commands
composer install --no-dev
php artisan key:generate --force
php artisan storage:link
php artisan migrate --force

# Optional: Cache configuration for production
php artisan config:cache
php artisan route:cache
