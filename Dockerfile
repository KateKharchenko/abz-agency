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

# Copy app files
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose port
EXPOSE 8080

# Custom startup script
CMD ["/bin/sh", "-c", "\
    # Create .env from .env.example if missing \
    if [ ! -f .env ]; then \
      echo 'Creating .env file...'; \
      cp .env.example .env; \
    fi && \
    \
    # Generate app key if not set \
    if ! grep -q '^APP_KEY=' .env || grep -q '^APP_KEY=$' .env; then \
      echo 'Generating APP_KEY...'; \
      php artisan key:generate; \
    fi && \
    \
    # Run migrations \
    php artisan migrate --force && \
    \
    # Check if database is empty and run seeders only once \
    if [ \"$(php artisan db:seed:status | grep 'Ran?' | grep -c 'No')\" -gt 0 ]; then \
      echo 'Running seeders...'; \
      php artisan db:seed --force; \
    else \
      echo 'Seeders already ran. Skipping.'; \
    fi && \
    \
    # Start Laravel dev server \
    php artisan serve --host=0.0.0.0 --port=8080"]
