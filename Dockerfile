# syntax=docker/dockerfile:1.7-labs

FROM dunglas/frankenphp:1.4-php8.3 AS frankenphp_upstream

FROM frankenphp_upstream AS builder

WORKDIR /app

# System dependencies for common Symfony needs (DB and zip handling).
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

RUN install-php-extensions \
    pdo_mysql \
    intl \
    zip \
    opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock symfony.lock ./
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --no-scripts

COPY . .

RUN composer dump-autoload --classmap-authoritative --no-dev \
    && mkdir -p var/cache var/log \
    && php bin/console cache:clear --env=prod --no-warmup \
    && php bin/console cache:warmup --env=prod

FROM frankenphp_upstream AS runner

WORKDIR /app

ENV APP_ENV=prod
ENV APP_DEBUG=0
ENV SERVER_NAME=:80

RUN install-php-extensions \
    pdo_mysql \
    intl \
    zip \
    opcache

COPY --from=builder /app /app

RUN mkdir -p /app/public/uploads \
    && chown -R www-data:www-data /app/var /app/public/uploads

EXPOSE 80

CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
