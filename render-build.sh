#!/usr/bin/env bash
set -o errexit

# Install composer locally
EXPECTED_CHECKSUM="$(wget -q -O - https://composer.github.io/installer.sig)"
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"

if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]; then
    >&2 echo 'ERROR: Invalid composer installer checksum'
    exit 1
fi

php composer-setup.php --quiet
rm composer-setup.php

# Run composer and Laravel commands
php composer.phar install --no-dev --no-interaction --prefer-dist
php artisan key:generate --force
php artisan storage:link
php artisan migrate --force
php artisan config:cache
php artisan route:cache
