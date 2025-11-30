FROM php:8.4.1

RUN set -eux \
 && curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - \
 && apt-get update -yqq \
 && apt-get install -y --no-install-recommends \
    git \
    nodejs \
    unzip \
 && docker-php-ext-install \
    pcntl \
    pdo_mysql \
 && pecl install opentelemetry \
 && docker-php-ext-enable opentelemetry \
 && apt-get autoremove -yqq --purge \
 && apt-get clean -y \
 && :

COPY --from=composer /usr/bin/composer /usr/bin/composer
