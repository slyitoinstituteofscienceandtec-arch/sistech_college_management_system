#!/bin/bash
set -ex

MARKER="storage/app/.deployed"

if [ -f "$MARKER" ]; then
    php artisan migrate --force || true
else
    php artisan migrate:fresh --force || true
    php artisan db:seed --force || true
    touch "$MARKER"
fi

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

exec php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
