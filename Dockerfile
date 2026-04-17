# Step 1: Build Assets with Node
FROM node:20 as asset-builder
WORKDIR /app
COPY . .
RUN npm install && npm run build

# Step 2: Setup PHP Environment
FROM php:8.2-cli

WORKDIR /var/www

# Install all system dependencies from your Nix list
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libpng-dev libjpeg-dev \
    libfreetype6-dev libzip-dev libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd zip intl bcmath

# Copy project files
COPY . .

# Copy the compiled assets from the first step
COPY --from=asset-builder /app/public/build ./public/build

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 10000

# Start server 
CMD php artisan config:clear && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000