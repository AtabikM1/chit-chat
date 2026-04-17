# Menggunakan image resmi PHP 8.3 versi FPM (FastCGI Process Manager)
FROM php:8.3-fpm

# Install dependensi sistem operasi Linux yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Bersihkan cache untuk memperkecil ukuran container
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install ekstensi PHP (termasuk pdo_mysql untuk database)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Ambil Composer langsung dari image resminya
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set folder kerja di dalam container
WORKDIR /var/www
