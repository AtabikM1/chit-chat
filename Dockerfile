
FROM php:8.3-fpm-alpine AS app_base

RUN apk update && apk add --no-cache \
    git curl libpng-dev libjpeg-turbo-dev freetype-dev oniguruma-dev libxml2-dev zip unzip mysql-client

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/chitchat
COPY . .
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-interaction --optimize-autoloader --no-dev
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
