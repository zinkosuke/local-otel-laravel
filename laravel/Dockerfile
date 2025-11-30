FROM php:8.4.1

WORKDIR /opt/laravel

RUN set -eux \
 && apt-get update -yqq \
 && apt-get install -y --no-install-recommends \
    git \
    unzip \
 && docker-php-ext-install pdo_mysql \
 && :

COPY --from=composer /usr/bin/composer /usr/bin/composer
