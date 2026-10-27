# ==============================================================================
# Stage 1: Frontend Asset Compilation (Node.js 22)
# ==============================================================================
FROM node:22-alpine AS frontend-builder
WORKDIR /app

# 1. Cache NPM dependencies layer
COPY package.json package-lock.json ./
RUN npm ci --no-audit --prefer-offline

# 2. Copy only frontend-relevant assets & build configuration
COPY tsconfig.json vite.config.js tailwind.config.js postcss.config.js ./
COPY resources ./resources

# 3. Compile and bundle production assets (vue-tsc + Vite)
RUN npm run build

# ==============================================================================
# Stage 2: PHP Application Runtime (PHP 8.3 CLI / Alpine)
# ==============================================================================
FROM php:8.3-cli-alpine AS runtime

LABEL maintainer="DevPulse Team"
LABEL description="High-velocity productivity suite for developers"

# 1. Install production system libraries & PostgreSQL client
RUN apk add --no-cache \
    postgresql-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    curl \
    git \
    unzip \
    bash \
    && rm -rf /var/cache/apk/*

# 2. Compile & enable required PHP core extensions
RUN docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        pgsql \
        zip \
        pcntl \
        bcmath \
        intl \
        opcache

# 3. Install official Composer binary from official image
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 4. BEST PRACTICE: Cache Composer dependencies layer
# By copying composer.json and composer.lock first, Docker caches this layer.
# Future PHP code changes will NOT re-download Composer packages!
COPY composer.json composer.lock ./
RUN composer install \
    --no-interaction \
    --prefer-dist \
    --no-dev \
    --no-scripts \
    --no-autoloader

# 5. Copy the rest of the application codebase
COPY . .

# 6. Copy compiled frontend assets from Stage 1
COPY --from=frontend-builder /app/public/build ./public/build

# 7. Generate production optimized autoloader
RUN composer dump-autoload --optimize --no-dev

# 8. Set up application directory permissions
RUN mkdir -p storage/framework/cache/data \
             storage/framework/sessions \
             storage/framework/views \
             storage/logs \
             bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# 9. Configure entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose HTTP application port
EXPOSE 8000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
