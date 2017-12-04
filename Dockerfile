FROM php:7.1-cli
COPY . /usr/src/phpincludetest
WORKDIR /usr/src/phpincludetest
RUN docker-php-ext-install opcache
