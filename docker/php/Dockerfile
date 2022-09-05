FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    curl \
    zip \
    nano \
    unzip \
    libicu-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY ./xdebug.ini "${PHP_INI_DIR}/conf.d"

WORKDIR /var/www
