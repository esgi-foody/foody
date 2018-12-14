FROM php:7.3-fpm

RUN apt-get update && apt-get install -y libzip-dev libpq-dev git zlib1g-dev unzip && docker-php-ext-install pdo pdo_pgsql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
