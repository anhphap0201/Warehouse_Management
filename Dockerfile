FROM php:8.2-fpm

# Cài tiện ích
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip unzip curl git \
    nodejs npm \
    libmysqlclient-dev

RUN docker-php-ext-install pdo pdo_mysql
# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project
WORKDIR /var/www
COPY . .

# Cài Laravel
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# Set quyền
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
