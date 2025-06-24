# Use official PHP image with Apache
FROM php:8.2-apache

# Install mysqli and other common PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite (if you use pretty URLs)
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy website files into container
COPY . /var/www/html/

# Set permissions for upload folder
RUN mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html/uploads \
    && chmod -R 755 /var/www/html/uploads

# Expose port 80 (default for Apache)
EXPOSE 80
