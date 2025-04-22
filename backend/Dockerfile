FROM phpdockerio/php:8.3-fpm
WORKDIR "/application"

RUN apt-get update \
    && apt-get -y --no-install-recommends install \
    git \
    php8.3-mysql \
    && apt-get clean \
    && apt-get -y install php8.3-sqlite3 \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*