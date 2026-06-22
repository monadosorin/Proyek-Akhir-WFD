FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
        git curl zip unzip libpng-dev libzip-dev oniguruma-dev icu-dev nodejs npm \
        bash mysql-client \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction

COPY package.json package-lock.json* ./
RUN npm ci --no-audit --no-fund

COPY . /var/www

RUN composer dump-autoload --optimize \
    && npm run build \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
