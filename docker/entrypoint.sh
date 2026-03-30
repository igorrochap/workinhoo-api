#!/bin/sh
set -e

if [ ! -f "vendor/autoload.php" ]; then
    echo "[entrypoint] Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist
fi

if [ -z "$(grep '^APP_KEY=.\+' .env 2>/dev/null)" ]; then
    echo "[entrypoint] Generating application key..."
    php artisan key:generate
fi

echo "[entrypoint] Running migrations..."
php artisan migrate --force

echo "[entrypoint] Ready."

exec "$@"