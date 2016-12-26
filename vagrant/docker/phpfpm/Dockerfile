FROM php:7.1-fpm

ENV container=docker

RUN apt-get update -y

RUN apt-get install -y git zlib1g-dev libfreetype6-dev libjpeg62-turbo-dev libmcrypt-dev libpng12-dev libmemcached-dev libxml2-dev supervisor cron

RUN docker-php-ext-install pdo pdo_mysql zip iconv mcrypt mbstring bcmath sockets opcache soap

RUN docker-php-ext-configure gd \
        --enable-gd-native-ttf \
        --with-freetype-dir=/usr/include/freetype2 \
        --with-png-dir=/usr/include \
        --with-jpeg-dir=/usr/include \
    && docker-php-ext-install gd

RUN pecl install redis-3.1.0 \
    && pecl install xdebug-2.5.0 \
    && docker-php-ext-enable redis xdebug

RUN curl -L -o /tmp/memcached.tar.gz "https://github.com/php-memcached-dev/php-memcached/archive/php7.tar.gz" \
    && mkdir -p /usr/src/php/ext/memcached \
    && tar -C /usr/src/php/ext/memcached -zxvf /tmp/memcached.tar.gz --strip 1 \
    && docker-php-ext-configure memcached \
    && docker-php-ext-install memcached \
    && rm /tmp/memcached.tar.gz

RUN mkdir -p /var/log/supervisor
RUN chmod +x /etc/init.d/supervisor
COPY php.ini /usr/local/etc/php/

RUN mkdir -p /usr/share/crontab/

# Add our crontab file
COPY crontab.conf /usr/share/crontab/crontab.conf

# Use the crontab file.
ARG RUN_CRON=yes
RUN if [ "$RUN_CRON" = "yes" ] ; then crontab /usr/share/crontab/crontab.conf; fi

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sL https://deb.nodesource.com/setup_6.x | bash -
RUN apt-get install -y nodejs bzip2

RUN npm set progress=false  && \
	echo '{ "allow_root": true }' > /root/.bowerrc

EXPOSE 9000

VOLUME ["/var/www/application/"]
WORKDIR /var/www/application
