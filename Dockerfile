FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip curl libpng-dev \
    libonig-dev libxml2-dev git \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-scripts

RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

RUN echo '<VirtualHost *:80>\n    DocumentRoot /var/www/html/public\n    <Directory /var/www/html/public>\n        AllowOverride All\n        Require all granted\n    </Directory>\n</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

EXPOSE 80
CMD ["bash", "-c", "a2dismod mpm_event mpm_worker 2>/dev/null || true && a2enmod mpm_prefork rewrite 2>/dev/null || true && php artisan config:clear && php artisan migrate --force && php artisan storage:link 2>/dev/null || true && apache2-foreground"]
