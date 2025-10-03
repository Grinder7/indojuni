FROM php:8.2.12-fpm-alpine

RUN apk add --no-cache postgresql-dev

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN docker-php-ext-install pdo pdo_pgsql pgsql && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-scripts
COPY . .
EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port 8000

