FROM php:7-fpm

ENV container=docker

RUN apt-get update -y
RUN apt-get install git -y

RUN apt-get install -y zlib1g-dev libfreetype6-dev libjpeg62-turbo-dev libmcrypt-dev libpng12-dev libmemcached-dev libxml2-dev supervisor
RUN docker-php-ext-install -j$(nproc) pdo pdo_mysql zip iconv mcrypt gd bcmath sockets opcache soap mbstring

RUN curl -L -o /tmp/memcached.tar.gz "https://github.com/php-memcached-dev/php-memcached/archive/php7.tar.gz" \
    && mkdir -p /usr/src/php/ext/memcached \
    && tar -C /usr/src/php/ext/memcached -zxvf /tmp/memcached.tar.gz --strip 1 \
    && docker-php-ext-configure memcached \
    && docker-php-ext-install memcached \
    && rm /tmp/memcached.tar.gz

RUN curl -L -o /tmp/xdebug.tar.gz "http://pecl.php.net/get/xdebug-2.4.0.tgz" \
    && mkdir -p /usr/src/php/ext/xdebug \
    && tar -C /usr/src/php/ext/xdebug -zxvf /tmp/xdebug.tar.gz --strip 1 \
    && docker-php-ext-configure xdebug \
    && docker-php-ext-install xdebug \
    && rm /tmp/xdebug.tar.gz


RUN curl -L -o /tmp/redis.tar.gz https://github.com/phpredis/phpredis/archive/php7.tar.gz \
    && tar xfz /tmp/redis.tar.gz \
    && rm -r /tmp/redis.tar.gz \
    && mv phpredis-php7 /usr/src/php/ext/redis \
    && docker-php-ext-install redis


RUN mkdir -p /var/log/supervisor
RUN chmod +x /etc/init.d/supervisor
COPY php.ini /usr/local/etc/php/

RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

RUN curl -sL https://deb.nodesource.com/setup_6.x | bash -
RUN apt-get install -y nodejs bzip2

RUN npm set progress=false  && \
	echo '{ "allow_root": true }' > /root/.bowerrc

EXPOSE 9000

VOLUME ["/var/www/application/"]
WORKDIR /var/www/application
