FROM phpdockerio/php:7.4-fpm

WORKDIR "/application"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install  php7.4-mysql \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Add AMQP extension
RUN apk add \
        --repository http://dl-cdn.alpinelinux.org/alpine/v3.6/main \
        --no-cache \
        rabbitmq-c-dev \
        && pecl install amqp \
        && docker-php-ext-enable amqp

# Install git
RUN apt-get update \
    && apt-get -y install git \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install nano, vim and curl
RUN apt-get update \
    && apt-get -y install nano vim curl \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*