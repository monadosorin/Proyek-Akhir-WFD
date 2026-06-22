#!/bin/bash
set -e

cd /var/www

until php -r "new PDO('mysql:host=$DB_HOST;port=$DB_PORT', '$DB_USERNAME', '$DB_PASSWORD');" 2>/dev/null; do
    echo "Waiting for MySQL..."
    sleep 2
done

php artisan migrate --force --no-interaction
php artisan db:seed --force --no-interaction || true
php artisan config:cache
php artisan route:cache

exec "$@"
