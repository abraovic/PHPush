##
#   Dockerfile for simple php driven projects. It includes
#   following software:
#       - Ubuntu 16.04 LTS
#       - PHP 7.1
#       - supervisor
#       - CLI apps: vim, curl, wget, buld-essensials
#
#   Disclamer: This Docker file is used as is, without any warranty!
##

FROM ubuntu:16.04
MAINTAINER Ante Braovic <abraovic@gmail.com>

ENV DEBIAN_FRONTEND noninteractive
ENV PHP_VERSION 7.1

RUN usermod -u 1000 www-data
RUN apt-get update && apt-get install -y \
    supervisor \
    vim \
    wget \
    build-essential \
    nghttp2 \
    libnghttp2-dev \
    libssl-dev \
    snmp \
    language-pack-en \
    git \
    software-properties-common
# PHP 7.1 support
RUN LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php
RUN apt-get update && apt-get install -y \
    php${PHP_VERSION} \
    php${PHP_VERSION}-cli \
    php${PHP_VERSION}-curl \
    php${PHP_VERSION}-mbstring \
    php${PHP_VERSION}-dev \
    php${PHP_VERSION}-gmp \
    php${PHP_VERSION}-xml \
    php${PHP_VERSION}-zip \
&& rm -rf /var/lib/apt/lists/*

# Composer install
COPY ./.docker/config/install.sh /tmp/install.sh
RUN chmod +x /tmp/install.sh
RUN /tmp/install.sh

## Configure
COPY ./.docker/config/php.ini /etc/php/${PHP_VERSION}/cli/
COPY ./.docker/config/supervisor.conf /etc/supervisor/conf.d/supervisor.conf

CMD ["/usr/bin/supervisord"]