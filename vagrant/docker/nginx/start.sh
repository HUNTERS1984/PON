#!/bin/sh


mkdir -p /var/lib/php/session
chown -R nginx:nginx /var/lib/php/session
chmod -R 777 /var/lib/php/session


if [ -n "${DOMAIN}" ]; then
    sed -i -e "s/pon.dev/${DOMAIN}/g" /etc/nginx/conf.d/pon.conf
fi

if [ -n "${ENVPON}" ]; then
    sed -i -e "s/app_dev/${ENVPON}/g" /etc/nginx/conf.d/pon.conf
fi

/usr/sbin/nginx
