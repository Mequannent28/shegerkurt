FROM php:8.2-apache

# Install database extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Enable mod_rewrite
RUN a2enmod rewrite

# Copy project files
COPY . /var/www/html/

# Secure permissions
RUN chown -R www-data:www-data /var/www/html

# Create a startup script to dynamically configure the Render PORT
RUN echo '#!/bin/bash\n\
if [ -z "$PORT" ]; then\n\
  PORT=80\n\
fi\n\
sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf\n\
echo "ServerName localhost" >> /etc/apache2/apache2.conf\n\
source /etc/apache2/envvars\n\
exec apache2 -D FOREGROUND\n\
' > /usr/local/bin/start.sh

RUN chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]
