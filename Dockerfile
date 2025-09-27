FROM php:8.2-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
 && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

RUN chown -R www-data:www-data /var/www

EXPOSE 9000
CMD ["php-fpm"]

