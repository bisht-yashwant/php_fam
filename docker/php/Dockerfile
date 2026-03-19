# Stage 1: dependencies
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader

COPY . .

# Stage 2: runtime
FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

# Install PHP extensions
RUN apk add --no-cache \
    mysql-client \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libzip-dev \
    zip unzip

RUN docker-php-ext-install pdo pdo_mysql

# Copy full project from builder
COPY --from=vendor /app /var/www/html