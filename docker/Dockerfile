FROM php:7.4-apache-buster

RUN a2enmod rewrite negotiation

COPY . /var/www

COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/config/php/php.ini /usr/local/etc/php/php.ini

# Install extensions
RUN docker-php-ext-install pdo_mysql

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

WORKDIR /var/www




