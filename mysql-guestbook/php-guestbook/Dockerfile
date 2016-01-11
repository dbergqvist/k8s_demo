FROM php:5-apache

RUN apt-get update && \
        apt-get install -y libmemcached-dev zlib1g-dev libmysqlclient-dev && \
        pecl install memcached && \
        docker-php-ext-install pdo pdo_mysql

ADD setup.php /var/www/html/setup.php
ADD index.php /var/www/html/index.php
ADD controllers.js /var/www/html/controllers.js
ADD index.html /var/www/html/index.html
ADD php.ini /usr/local/etc/php/php.ini
