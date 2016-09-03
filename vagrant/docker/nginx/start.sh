#!/bin/sh


mkdir -p /var/lib/php/session
chown -R nginx:nginx /var/lib/php/session
chmod -R 777 /var/lib/php/session

/usr/sbin/nginx
