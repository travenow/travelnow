FROM php:8.2-apache

# Install mysqli and other extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy your code into the container
COPY . /var/www/html/

# Enable Apache mod_rewrite if needed
RUN a2enmod rewrite
