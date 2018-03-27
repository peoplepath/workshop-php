FROM php:7.2-cli-alpine

MAINTAINER Zdeněk Zahoř <zdenek.zahor@gmail.com>

RUN \
    apk update && \
    apk add autoconf g++ make && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    # TODO Optimlaizovat image
    curl --silent --location https://raw.githubusercontent.com/composer/getcomposer.org/master/web/installer | php -- --install-dir=/usr/local/bin --filename=composer
