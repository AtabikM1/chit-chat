FROM php:8.3-fpm-alpine AS app_base

# Install system dependencies (Ditambahkan libzip-dev, linux-headers, dan build-base untuk kestabilan)
RUN apk update && apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    libzip-dev \
    linux-headers \
    build-base \
    zip \
    unzip \
    mysql-client

# Install PHP extensions (Ditambahkan 'zip' agar autoloader Composer tidak crash)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/chitchat

# Copy source code
COPY . .

# Pastikan file .env bawaan lokal tidak ikut masuk mengotori image produksi
RUN rm -f .env

# Install composer dependencies untuk production
ENV COMPOSER_ALLOW_SUPERUSER=1

# UBAH INI: Tambahkan flag --ignore-platform-req=ext-http atau sejenisnya jika ada package luar yang rewel
RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs --no-scripts
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative
# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
