# ==========================================
# Stage 1: Composer 依存関係の解決
# ==========================================
FROM composer:2 AS composer-builder

WORKDIR /app

COPY composer.json composer.lock ./

# 依存パッケージの解決（Autoloader は Stage 3 で生成）
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --no-scripts \
    --no-autoloader \
    --ignore-platform-reqs

# 【セキュリティ対策】脆弱性のあるパッケージが含まれる場合はビルドを即座に停止
RUN composer audit --locked

# ==========================================
# Stage 2: Frontend Build (Node.js 脆弱性・セキュリティ対策済み)
# ==========================================
FROM node:22-bookworm-slim AS frontend-builder

WORKDIR /app

RUN apt-get update \
    && apt-get upgrade -y \
    && rm -rf /var/lib/apt/lists/*

COPY package*.json ./

# 悪意あるライフサイクルスクリプトの実行を阻止
RUN npm ci --ignore-scripts --prefer-offline

# High / Critical の脆弱性がある場合はビルド停止
RUN npm audit --audit-level=high

# Tailwind / Blade / Flux 抽出用の vendor を配置
COPY --from=composer-builder /app/vendor ./vendor

COPY vite.config.* ./
COPY tailwind.config.* ./
COPY postcss.config.* ./
COPY resources/ ./resources/
COPY routes/ ./routes/
COPY public/ ./public/

RUN npm run build

# ==========================================
# Stage 3: Production Server (FrankenPHP + Octane on GKE)
# ==========================================
FROM dunglas/frankenphp:php8.3

RUN apt-get update \
    && apt-get upgrade -y \
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

# 本番 php.ini 配置とヘッダー隠蔽
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && { \
        echo 'expose_php=Off'; \
    } > "$PHP_INI_DIR/conf.d/security.ini"

RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.enable_cli=1'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.max_accelerated_files=20000'; \
    echo 'opcache.memory_consumption=256'; \
} > "$PHP_INI_DIR/conf.d/opcache-recommended.ini"

RUN php -m | grep -i opentelemetry \
    && php --ri opentelemetry

WORKDIR /app

# Composer バイナリのコピー
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# アプリケーションファイルを www-data 所有でコピー
COPY --chown=www-data:www-data --from=composer-builder /app/vendor ./vendor
COPY --chown=www-data:www-data composer.json composer.lock ./
COPY --chown=www-data:www-data . .

# 不要な環境設定や古いキャッシュファイルを排除
RUN rm -f .env bootstrap/cache/*.php

# 静的ビルド成果物のみを配置
COPY --from=frontend-builder --chown=www-data:www-data /app/public/build public/build

# 最適化 Autoloader の生成
RUN composer dump-autoload \
    --optimize \
    --no-dev \
    --classmap-authoritative

# ビルド後に Composer を削除（コンテナ攻撃面の縮小）
RUN rm /usr/bin/composer

# ディレクトリ作成と権限設定
# Octane のサーバー状態ファイル保存先 (storage/logs) を確実に作成・権限付与
RUN mkdir -p \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data \
        /app/storage \
        /app/bootstrap/cache \
    && chmod -R 775 \
        /app/storage \
        /app/bootstrap/cache

ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    OTEL_SERVICE_NAME=myapp \
    OTEL_EXPORTER_OTLP_PROTOCOL=http/protobuf

EXPOSE 8000

# 【GKEセキュリティ標準】非特権ユーザー（UID/GID 33: www-data）で実行
USER www-data

# オプションを正規のものに修正
CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000", "--workers=auto", "--max-requests=500"]