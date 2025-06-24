# Use an official PHP image with Apache
FROM php:8.1-apache

# Copy all project files to Apache's web root
COPY . /var/www/html/

# Enable Apache mod_rewrite (optional for .htaccess)
RUN a2enmod rewrite
