FROM php:8.2-apache

# Install MySQL extensions for PHP
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Enable Apache mod_rewrite for nice URLs
RUN a2enmod rewrite

# Configure Apache to use the $PORT environment variable provided by Render
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copy all the project files into the Apache document root
COPY . /var/www/html/

# Fix permissions
RUN chown -R www-data:www-data /var/www/html

