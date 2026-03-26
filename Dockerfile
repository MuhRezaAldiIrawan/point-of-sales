FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libpng16-16 \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    nginx \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql mbstring exif pcntl bcmath gd zip intl

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock ./

# Debug sementara, bisa dihapus kalau sudah sukses
RUN php -m && composer --version

RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

COPY . .

RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]