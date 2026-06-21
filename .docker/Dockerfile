FROM php:8.4-cli
LABEL maintainer="chanshige <dev@shigeki.tokyo>"

RUN apt-get update
RUN apt-get install -y libicu-dev libpng-dev wget unzip git libonig-dev \
      && docker-php-ext-install intl gd mbstring \
      && cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

# composer
COPY --from=composer /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_NO_INTERACTION=1
ENV COMPOSER_MEMORY_LIMIT=-1
# xdebug
RUN pecl install xdebug \
      && docker-php-ext-enable xdebug
# work dir
RUN mkdir /workspace
WORKDIR /workspace
