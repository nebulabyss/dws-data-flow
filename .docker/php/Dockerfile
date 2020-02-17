ARG PHP_VERSION=""
FROM php:${PHP_VERSION:+${PHP_VERSION}-}fpm-alpine

RUN apk update; \
    apk upgrade;

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN apk add shadow && usermod -u 1000 www-data && groupmod -g 1000 www-data

RUN rm -rf /var/cache/apk/*
