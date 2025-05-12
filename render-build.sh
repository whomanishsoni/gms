#!/usr/bin/env bash
set -o errexit

# Verify PHP is available
php -v

# Install Composer locally (no system install needed)
EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')"
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"

if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]; then
    echo 'ERROR: Invalid composer installer checksum'
    exit 1
fi

php composer-setup.php --install-dir=bin --filename=composer
rm composer-setup.php

# Run composer with local binary
./bin/composer install --no-dev --no-interaction --prefer-dist

# Laravel setup
php artisan key:generate --force
php artisan storage:link
php artisan migrate --force
php artisan config:cache
php artisan route:cache
