#!/bin/sh

#echo "export SYMFONY__DATABASE__HOST=${MYSQL_PORT_3306_TCP_ADDR}" >> ~/.bashrc
#source ~/.bashrc
mkdir -p /var/lib/php/session
chmod -R 777 /var/lib/php/session
chmod -R 777 /var/www/pon/var/cache
chmod -R 777 /var/www/pon/var/logs

/usr/local/sbin/php-fpm

