FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libonig-dev libxml2-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install pdo_mysql zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy Laravel project files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose port
EXPOSE 8080

CMD ["/bin/sh", "-c", "\
    [ -f .env ] || php -r \"file_put_contents('.env', file_get_contents('.env.example'));\" && \
    php artisan key:generate && \
    php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=8080"]

