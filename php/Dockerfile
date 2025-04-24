FROM php:8.2-fpm

# ARGs para proxy
ARG HTTP_PROXY
ARG HTTPS_PROXY
ARG NO_PROXY

ENV HTTP_PROXY=$HTTP_PROXY \
    http_proxy=$HTTP_PROXY \
    HTTPS_PROXY=$HTTPS_PROXY \
    https_proxy=$HTTPS_PROXY \
    NO_PROXY=$NO_PROXY \
    no_proxy=$NO_PROXY

# Instala extensões necessárias
RUN apt-get update && apt-get install -y \
    zip unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev git \
    && docker-php-ext-install pdo_mysql mbstring zip

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
