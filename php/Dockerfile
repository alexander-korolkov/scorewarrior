FROM php:8.3-fpm

WORKDIR /var/www/public/

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install pdo mysqli pdo_mysql

RUN chown -R www-data:www-data /var/www/
RUN chmod -R 776 /var/www/
