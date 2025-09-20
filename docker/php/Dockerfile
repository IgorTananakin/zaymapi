FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev

RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN mkdir -p runtime web/assets

RUN chmod -R 755 web/assets && \
    chmod -R 755 runtime && \
    chown -R www-data:www-data web/assets && \
    chown -R www-data:www-data runtime && \
    chown -R www-data:www-data /var/www

EXPOSE 9000
CMD ["php-fpm"]