FROM php:7.3-apache

RUN docker-php-ext-install pdo_mysql

# Set our application folder as an environment variable
ENV APP_HOME /var/www/html

# Restart web server
RUN service apache2 restart

EXPOSE 80