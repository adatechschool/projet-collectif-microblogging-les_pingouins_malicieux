#!/bin/bash

# Disable XDebug because it is uglifully slow on Windows+WSL
mv /usr/local/etc/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini.disabled


apt-get update

# Install Postgre PDO
apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    # configure the GD extension to include support for JPEG and PNG image formats
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Install pcov extension
pecl install pcov
# Enable pcov extension
docker-php-ext-enable pcov