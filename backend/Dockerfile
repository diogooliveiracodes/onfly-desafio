FROM php:8.3-fpm

WORKDIR "/application"

RUN apt-get update \
    && apt-get -y --no-install-recommends install \
    git \
    libsqlite3-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql pdo_sqlite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

