# Use official PHP image with Apache
FROM php:8.2-apache

# Install dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer globally
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy website files into container
COPY . /var/www/html/

# Set correct permissions for upload folders
RUN mkdir -p /var/www/html/uploads /var/www/html/uploads/gallery \
    && chown -R www-data:www-data /var/www/html/uploads \
    && chmod -R 755 /var/www/html/uploads

# Install PHP dependencies via Composer
RUN composer install --no-dev --optimize-autoloader

# Expose Apache HTTP port
EXPOSE 80
