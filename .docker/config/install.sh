#!/usr/bin/env bash

cd ~

wget https://curl.haxx.se/download/curl-7.58.0.tar.gz
tar -xvf curl-7.58.0.tar.gz
cd curl-7.58.0
./configure --with-nghttp2 --prefix=/usr/local --with-ssl=/usr/local/ssl
make
make install
ldconfig

curl -sS https://getcomposer.org/installer -o composer-setup.php
php composer-setup.php --install-dir=/usr/local/bin --filename=composer