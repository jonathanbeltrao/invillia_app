FROM  php:7.3-fpm-alpine as php

RUN curl -sS https://getcomposer.org/installer | php && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer
RUN set -ex \
  && apk --no-cache add
RUN  docker-php-ext-install bcmath sockets pdo_mysql mysqli

WORKDIR /data/invillia_app/
COPY . /data/invillia_app/

RUN composer install -o

COPY ./.docker/php-fpm/php.ini /usr/local/etc/php/php.ini
COPY ./.docker/php-fpm/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./.docker/php-fpm/php-fpm.conf /usr/local/etc/php-fpm.conf

FROM php as apis

EXPOSE 9000
