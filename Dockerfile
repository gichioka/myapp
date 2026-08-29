FROM php:8.3-cli

WORKDIR /var/www/html

# 必要パッケージ
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

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# OpenTelemetry が本当に有効になっているか確認
RUN php -m | grep -i opentelemetry
RUN php --ri opentelemetry

# Azure MySQL 用 CA 証明書
RUN curl -L https://cacerts.digicert.com/DigiCertGlobalRootG2.crt.pem \
    -o /var/www/html/DigiCertGlobalRootG2.crt.pem

# Laravel プロジェクト
COPY . .

# ローカルの .env はイメージに入れない
RUN rm -f .env

# Composer
RUN composer install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# Laravel の書き込み権限
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]