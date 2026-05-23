FROM php:8.2-fpm-alpine

# 1. Instal seluruh library sistem operating Alpine yang dibutuhkan PHP & Composer
RUN apk add --no-cache \
    build-base \
    shadow \
    supervisor \
    nginx \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    bash \
    mysql-client \
    mariadb-connector-c-dev \
    libxml2-dev \
    oniguruma-dev \
    curl-dev

# 2. Compile ekstensi PHP secara lengkap untuk kestabilan Laravel 11
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        gd \
        pdo \
        pdo_mysql \
        zip \
        bcmath \
        opcache \
        xml \
        dom \
        mbstring \
        curl

# Instal Composer terbaru
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Atur folder kerja
WORKDIR /var/www/html

# Salin seluruh source code proyek
COPY . .

# Jalankan instalasi composer untuk production
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs

# Atur izin folder storage dan bootstrap cache agar bisa menulis log
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=80
