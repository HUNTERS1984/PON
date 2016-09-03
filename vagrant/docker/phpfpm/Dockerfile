FROM php:7-fpm

ENV container=docker

RUN apt-get update -y
RUN apt-get install git-all -y

RUN apt-get install -y zlib1g-dev libfreetype6-dev libjpeg62-turbo-dev libmcrypt-dev libpng12-dev libmemcached-dev
RUN docker-php-ext-install -j$(nproc) pdo pdo_mysql zip iconv mcrypt gd

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

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('SHA384', 'composer-setup.php') === 'e115a8dc7871f15d853148a7fbac7da27d6c0030b848d9b3dc09e2a0388afed865e6a3d6b3c0fad45c48e2b5fc1196ae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php --install-dir=/usr/local/sbin --filename=composer
RUN php -r "unlink('composer-setup.php');"

RUN curl -sL https://deb.nodesource.com/setup_6.x | bash -
RUN apt-get install -y nodejs

RUN npm set progress=false  && \
	echo '{ "allow_root": true }' > /root/.bowerrc

ADD start.sh /start.sh
RUN chmod a+x /start.sh

EXPOSE 9000

WORKDIR /var/www/pon

ENTRYPOINT ["/start.sh"]
CMD ["composer"]