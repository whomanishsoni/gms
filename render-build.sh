#!/usr/bin/env bash
set -o errexit

# Install composer dependencies
composer install --no-dev --no-interaction --prefer-dist

# Run Laravel setup
php artisan key:generate --force
php artisan storage:link
php artisan migrate --force

# Cache configuration for production
php artisan config:cache
php artisan route:cache
