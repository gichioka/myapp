FROM php:8.3-cli

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    autoconf \
    gcc \
    make \
    pkg-config \
    && docker-php-ext-install pdo_mysql zip \
    && pecl install opentelemetry \
    && docker-php-ext-enable opentelemetry \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Azure MySQL用のCA証明書
RUN curl -L https://cacerts.digicert.com/DigiCertGlobalRootG2.crt.pem \
    -o /var/www/html/DigiCertGlobalRootG2.crt.pem

COPY . .

RUN rm -f .env

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]