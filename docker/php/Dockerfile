FROM php:8.3-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    autoconf \
    gcc \
    make \
    && docker-php-ext-install \
    pdo_mysql \
    zip

RUN pecl install opentelemetry \
    && docker-php-ext-enable opentelemetry

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

CMD ["php-fpm"]