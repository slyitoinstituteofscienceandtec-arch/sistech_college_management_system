FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libpq-dev \
    && docker-php-ext-install pdo_pgsql pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN chmod -R 775 storage bootstrap/cache || true
RUN rm -f bootstrap/cache/*.php storage/framework/views/*.php storage/framework/sessions/* storage/logs/*.log

EXPOSE 10000

CMD php artisan migrate --force 2>&1; php artisan db:seed --force 2>&1; php -S 0.0.0.0:${PORT:-10000} server.php
