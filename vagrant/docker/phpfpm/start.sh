#!/bin/sh

#echo "export SYMFONY__DATABASE__HOST=${MYSQL_PORT_3306_TCP_ADDR}" >> ~/.bashrc
#source ~/.bashrc
mkdir -p /var/lib/php/session
chmod -R 777 /var/lib/php/session
chmod -R 777 /var/www/pon/var/cache
chmod -R 777 /var/www/pon/var/logs
chmod -R 777 /var/www/pon/var/node_modules
chmod -R 777 /var/www/pon/var/bower_components
setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx /var/www/pon/var/cache /var/www/pon/var/logs /var/www/pon/var/node_modules /var/www/pon/var/bower_components
setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx /var/www/pon/var/cache /var/www/pon/var/logs /var/www/pon/var/node_modules /var/www/pon/var/bower_components

/usr/local/sbin/php-fpm

