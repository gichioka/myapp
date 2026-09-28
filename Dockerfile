FROM dunglas/frankenphp:php8.3

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        ca-certificates \
        curl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*


RUN install-php-extensions \
    pdo_mysql \
    gd \
    pcntl \
    zip \
    bcmath \
    opcache \
    opentelemetry

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.enable_cli=1'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.max_accelerated_files=20000'; \
    echo 'opcache.memory_consumption=256'; \
} > "$PHP_INI_DIR/conf.d/opcache-recommended.ini"

RUN php -m | grep -i opentelemetry \
    && php --ri opentelemetry

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --no-scripts \
    --no-autoloader

COPY . .

# ★ ローカルの .env が本番環境に混入するのを防ぐために削除（ConfigMap のみを正しく読み込ませるため）
RUN rm -f .env

# ローカルでビルドしたViteの成果物をコンテナに確実に同梱
COPY --chown=www-data:www-data public/build public/build

RUN rm -f bootstrap/cache/*.php

RUN composer dump-autoload \
    --optimize \
    --no-dev

RUN mkdir -p \
        storage/framework/cache \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache

RUN chown -R www-data:www-data \
        /app/storage \
        /app/bootstrap/cache \
    && chmod -R 775 \
        /app/storage \
        /app/bootstrap/cache

ENV APP_ENV=production \
    APP_DEBUG=false \
    OTEL_SERVICE_NAME=myapp \
    OTEL_EXPORTER_OTLP_PROTOCOL=http/protobuf

EXPOSE 8000

CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000", "--workers=auto", "--max-requests=500"]