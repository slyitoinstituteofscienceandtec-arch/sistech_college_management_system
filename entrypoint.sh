#!/bin/bash

MARKER="/var/www/html/storage/app/.deployed"

if [ -f "$MARKER" ]; then
    php artisan migrate --force
else
    php artisan migrate:fresh --force
    php artisan db:seed --force
    touch "$MARKER"
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan serve --host=0.0.0.0 --port=$PORT
