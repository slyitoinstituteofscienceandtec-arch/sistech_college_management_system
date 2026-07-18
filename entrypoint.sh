#!/bin/bash
set -e

MARKER="/var/www/html/storage/app/.deployed"

if [ -f "$MARKER" ]; then
    php artisan migrate --force 2>&1 || true
else
    php artisan migrate:fresh --force 2>&1 || true
    php artisan db:seed --force 2>&1 || true
    touch "$MARKER"
fi

php artisan config:cache 2>&1 || true
php artisan route:cache 2>&1 || true
php artisan view:cache 2>&1 || true

echo "Starting server on port ${PORT:-10000}..."
php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
