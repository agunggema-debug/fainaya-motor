FROM php:8.2-fpm-alpine

# Instal ekstensi sistem yang dibutuhkan Laravel
RUN apk add --no-repeat build-base shadow supervisor nginx libpng-dev libjpeg-turbo-dev freetype-dev libzip-dev zip unzip git bash mysql-client mysql-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql zip bcmath opcache

# Instal Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Atur folder kerja
WORKDIR /var/www/html

# Salin source code
COPY . .

# Jalankan instalasi composer untuk production
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Atur izin folder storage dan bootstrap cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Salin konfigurasi Nginx bawaan untuk Render
RUN cp /var/www/html/public/index.php /var/www/html/public/index.php

# Setup port otomatis dari Render
EXPOSE 80

CMD php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=80
