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
    no_proxy=$NO_PROXY \
    HOME=/var/www

# Instala extensões necessárias
RUN apt-get update && apt-get install -y \
    curl unzip zip git gnupg xdg-utils ca-certificates \
    libzip-dev libpng-dev libonig-dev libxml2-dev libnss3 libatk1.0-0 \
    libatk-bridge2.0-0 libcups2 libxcomposite1 libxrandr2 \
    libxdamage1 libxss1 libasound2 libpangocairo-1.0-0 libgtk-3-0 libgbm1 libdrm2 \
    libappindicator3-1 libxshmfence1 libnss3-tools  \
    fonts-freefont-ttf fonts-liberation \
    && docker-php-ext-install pdo_mysql mbstring gd
    
# Instala o google chrome
# RUN curl -fsSL https://dl.google.com/linux/linux_signing_key.pub | gpg --dearmor -o /etc/apt/trusted.gpg.d/google.gpg \
#     && echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" > /etc/apt/sources.list.d/google-chrome.list \
#     && apt-get update && apt-get install -y google-chrome-stable \
#     && apt-get clean && rm -rf /var/lib/apt/lists/*
    
# Limpa cache dos pacotes
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala o Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define diretório de trabalho
WORKDIR /var/www

# Copia o conteúdo do projeto para dentro do container
COPY . .

# Ajusta permissões das pastas usadas pelo Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache && \
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache
